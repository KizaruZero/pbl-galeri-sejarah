<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentVideo;
use App\Models\UserFavorite;
use App\Models\CategoryContent;
use App\Models\ContentReaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\MetadataVideo;
use Plutuss\Facades\MediaAnalyzer;
use Plutuss\GetID3\GetId3;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Imports\VideosImport;
use Maatwebsite\Excel\Facades\Excel;

class VideoController extends Controller
{
    //
    public function index()
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'user', 'categoryContents', 'contentReactions'])
            ->where('status', 'approved')
            ->get()
            ->map(function ($video) {
                $video->likes_count = $video->contentReactions->count();
                return $video;
            });

        if ($contentVideo->isEmpty()) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        return response()->json($contentVideo);
    }

    public function show($slug)
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'user', 'categoryContents', 'userComments', 'contentReactions'])
            ->where('status', 'approved')
            ->where('slug', $slug)
            ->first();

        if (!$contentVideo) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        $contentReactions = ContentReaction::where('content_video_id', $contentVideo->id)
            ->with('reactionType')
            ->count();
        $contentVideo->updateTotalViews();

        return response()->json([
            'video' => $contentVideo,
            'total_reactions' => $contentReactions
        ]);
    }

    public function getPopularVideos()
    {
        $popularVideos = ContentVideo::with([
            'metadataVideo',
            'user',
            'categoryContents',
            'contentReactions'
        ])
            ->where('status', 'approved')
            ->withCount([
                'contentReactions',
                'userComments',
                'userFavorite'
            ])
            ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 1) + (total_views * 0.5) + (user_favorite_count * 1) DESC')
            ->take(3)
            ->get()
            ->map(function ($video) {
                $video->likes_count = $video->contentReactions->count();
                return $video;
            });

        if ($popularVideos->isEmpty()) {
            return response()->json(['message' => 'No popular video found'], 404);
        }
        return response()->json($popularVideos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_url' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,mpeg,mpg,m4v,webm,mkv|max:102400', // 100MB max
            'thumbnail' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
            'source' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'required|exists:categories,id',
        ]);

        $userId = Auth::user()->id;
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Handle file upload
        if ($request->hasFile('video_url')) {
            $slug = Str::slug($request->title);
            $videoFile = $request->file('video_url');
            $extension = $videoFile->getClientOriginalExtension();

            // Generate filename
            $videoFilename = time() . '_' . $slug . '.' . $extension;

            // Store file in public storage
            $videoPath = $videoFile->storeAs('video_content', $videoFilename, 'public');

            // Save relative path to DB
            $videoUrl = 'video_content/' . $videoFilename;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $slug = Str::slug($request->title);
            $thumbnailFile = $request->file('thumbnail');
            $extensionThumbnail = $thumbnailFile->getClientOriginalExtension();

            // Generate filename
            $thumbnailFilename = time() . '_' . $slug . '.' . $extensionThumbnail;

            // Store file in public storage
            $thumbnailPath = $thumbnailFile->storeAs('thumbnail_video', $thumbnailFilename, 'public');

            // Save relative path to DB
            $thumbnailUrl = 'thumbnail_video/' . $thumbnailFilename;
        }

        $slug = Str::slug($request->title);

        // Create new video record
        $video = ContentVideo::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'source' => $request->source,
            'tag' => $request->tag,
            'user_id' => $userId,
            'video_url' => $videoUrl ?? null,
            'thumbnail' => $thumbnailUrl ?? null,
            'link_youtube' => $request->link_youtube,
            'status' => 'pending',
        ]);

        // Create category content associations for each selected category
        foreach ($request->category_ids as $categoryId) {
            CategoryContent::create([
                'category_id' => $categoryId,
                'content_photo_id' => null,
                'content_video_id' => $video->id,
            ]);
        }

        // Extract video metadata if video file was uploaded
        if ($request->hasFile('video_url')) {
            $this->extractAndSaveVideoMetadata($videoFile, $video->id);
        }

        return response()->json([
            'message' => 'Video uploaded successfully',
            'data' => $video->load(['metadataVideo', 'categoryContents'])
        ], 201);
    }

    /**
     * Extract metadata from video file and save to metadata_video table
     *
     * @param UploadedFile $videoFile
     * @param int $contentVideoId
     * @return void
     */
    private function extractAndSaveVideoMetadata($videoFile, $contentVideoId)
    {
        try {
            // Use MediaAnalyzer facade to analyze the uploaded file
            $media = MediaAnalyzer::uploadFile($videoFile);

            // Format file size
            $fileSize = $videoFile->getSize();

            // Extract codec information
            $codec = null;
            if ($media->getNestedValue('video.codec')) {
                $codec = 'Video: ' . $media->getNestedValue('video.codec');

                // Add audio codec if available
                if ($media->getNestedValue('audio.codec')) {
                    $codec .= ', Audio: ' . $media->getNestedValue('audio.codec');
                }
            }

            // Extract resolution
            $resolution = null;
            if ($media->getNestedValue('video.resolution_x') && $media->getNestedValue('video.resolution_y')) {
                $resolution = $media->getNestedValue('video.resolution_x') . 'x' . $media->getNestedValue('video.resolution_y');
            } elseif ($media->getResolution()) {
                $resolution = $media->getResolution();
            }

            // Extract frame rate (ensure it's a numeric value before rounding)
            $frameRate = $media->getNestedValue('video.frame_rate');
            if ($frameRate && is_numeric($frameRate)) {
                $frameRate = round($frameRate, 2);
            }

            // Extract duration (ensure it's a numeric value before rounding)
            $duration = null;
            if (isset($media->playtime_seconds) && is_numeric($media->playtime_seconds)) {
                $duration = round($media->playtime_seconds, 2);
            }

            // Create metadata record
            MetadataVideo::create([
                'content_video_id' => $contentVideoId,
                'file_size' => $fileSize,
                'format_file' => $media->getFileFormat(),
                'duration' => $duration,
                'codec_video_audio' => $codec,
                'resolution' => $resolution,
                'frame_rate' => $frameRate,
                'location' => null, // Location would need additional processing if available in metadata
                'collection_date' => now(),
            ]);

        } catch (\Exception $e) {
            // Log error but don't stop the upload process
            \Log::error('Failed to extract video metadata: ' . $e->getMessage());
        }
    }

    /**
     * Format file size to human-readable format
     *
     * @param int $bytes
     * @return string
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
    public function getVideoByUser($userId)
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'user', 'categoryContents', 'contentReactions', 'contentReactions.reactionType', 'userFavorite'])
            ->where('user_id', $userId)
            ->get()
            ->map(function ($video) {
                $video->likes_count = $video->contentReactions->count();
                return $video;
            });

        $totalLikes = $contentVideo->sum('likes_count');

        return response()->json([
            'videos' => $contentVideo,
            'total' => $contentVideo->count(),
            'total_likes' => $totalLikes

        ]);
    }

    public function updateVideoByUser(Request $request, $id)
    {
        $video = ContentVideo::findOrFail($id);

        $videoStatus = $video->status;
        if ($videoStatus == 'rejected') {
            $video->status = 'pending';
            $video->save();
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/flv,video/mpeg,video/mpg,video/m4v,video/webm,video/mkv',
            'thumbnail' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
            'source' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|url|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'metadata.collection_date' => 'nullable|date',
            'metadata.model' => 'nullable|string',
            'metadata.ISO' => 'nullable|string',
            'metadata.location' => 'nullable|string',
            'metadata.frame_rate' => 'nullable|string',
            'metadata.resolution' => 'nullable|string',
        ]);

        $data = [];
        $slug = Str::slug($request->title);

        // Handle video file if uploaded
        if ($request->hasFile('video_url')) {
            // Delete old video
            if ($video->video_url) {
                Storage::disk('public')->delete($video->video_url);
            }

            $videoFile = $request->file('video_url');
            $extension = $videoFile->getClientOriginalExtension();
            $videoFilename = time() . '_' . $slug . '.' . $extension;
            $videoPath = $videoFile->storeAs('video_content', $videoFilename, 'public');
            $data['video_url'] = 'video_content/' . $videoFilename;

            // Update metadata for new video
            $this->extractAndSaveVideoMetadata($videoFile, $video->id);
        }

        // Handle thumbnail if uploaded
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $thumbnailFile = $request->file('thumbnail');
            $extensionThumbnail = $thumbnailFile->getClientOriginalExtension();
            $thumbnailFilename = time() . '_' . $slug . $extensionThumbnail;
            $thumbnailPath = $thumbnailFile->storeAs('thumbnail_video', $thumbnailFilename, 'public');
            $data['thumbnail'] = 'thumbnail_video/' . $thumbnailFilename;
        }

        // Update text fields
        $fields = ['title', 'description', 'source', 'tag', 'link_youtube'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        // Update slug if title is changed
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Update video
        $video->update($data);

        // Update metadata if provided
        if ($request->has('metadata')) {
            $metadata = $video->metadataVideo()->updateOrCreate(
                ['content_video_id' => $video->id],
                [
                    'collection_date' => $request->input('metadata.collection_date'),
                    'model' => $request->input('metadata.model'),
                    'ISO' => $request->input('metadata.ISO'),
                    'location' => $request->input('metadata.location'),
                    'frame_rate' => $request->input('metadata.frame_rate'),
                    'resolution' => $request->input('metadata.resolution'),
                ]
            );
        }

        // Update category if provided
        if ($request->has('category_id')) {
            $video->categoryContents()->updateOrCreate(
                ['content_video_id' => $video->id],
                ['category_id' => $request->category_id]
            );
        }

        return response()->json([
            'message' => 'Video and metadata updated successfully',
            'data' => $video->fresh(['metadataVideo', 'categoryContents'])
        ]);
    }


    public function getFavoriteByUser($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->count();

        $data = UserFavorite::with([
            'contentVideo.metadataVideo',
            'contentVideo.userComments',
            'contentVideo.user',
            'contentVideo.categoryContents',
            'contentVideo.contentReactions',
            'contentVideo.contentReactions.reactionType'
        ])
            ->where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->get()
            ->map(function ($favorite) {
                if ($favorite->contentVideo) {
                    $favorite->contentVideo->likes_count = $favorite->contentVideo->contentReactions->count();
                }
                return $favorite;
            });

        return response()->json([
            'total' => $totalFavorites,
            'data' => $data,
        ]);
    }

    public function getVideoByCategory($slug)
    {
        $contentVideos = CategoryContent::with([
            'contentVideo.metadataVideo',
            'contentVideo.userComments',
            'contentVideo.user',
            'category',
            'contentVideo.contentReactions',
            'contentVideo.userFavorite'
        ])
            ->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->whereHas('contentVideo', function ($query) {
                $query->where('status', 'approved');
            })
            ->get()
            ->map(function ($categoryContent) {
                if ($categoryContent->contentVideo) {
                    $categoryContent->contentVideo->likes_count = $categoryContent->contentVideo->contentReactions->count();
                }
                return $categoryContent;
            });

        if ($contentVideos->isEmpty()) {
            return response()->json(['message' => 'Tidak ada konten ditemukan untuk kategori ini'], 404);
        }
        return response()->json($contentVideos);
    }

    public function edit($id)
    {
        $video = ContentVideo::with(['categoryContents'])->findOrFail($id);

        return response()->json([
            'video' => $video,
            'category_id' => $video->categoryContents->first()?->category_id
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        try {
            // Get the authenticated user's ID
            $userId = Auth::id();

            // Pass the user ID to the importer
            $import = new VideosImport($userId);

            Excel::import($import, $request->file('file'));

            $importedCount = $import->getRowCount();
            $skippedCount = $import->getSkippedCount();

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimpor {$importedCount} video",
                'skipped' => $skippedCount,
                'total' => $importedCount + $skippedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor: ' . $e->getMessage()
            ], 422);
        }
    }
}
