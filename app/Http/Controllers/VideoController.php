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


class VideoController extends Controller
{
    //
    public function index()
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'userComments.userReactions', 'user', 'categoryContents', 'contentReactions', 'contentReactions.reactionType', 'userFavorite'])
            ->where('status', 'approved')
            ->get();
        if (!$contentVideo) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        return response()->json($contentVideo);
    }

    public function show($slug)
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'user', 'categoryContents', 'contentReactions', 'contentReactions.reactionType', 'userFavorite'])
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
            'userComments',
            'user',
            'categoryContents',
            'contentReactions',
            'contentReactions.reactionType',
            'userFavorite'
        ])
        ->where('status', 'approved')
        ->withCount([
            'contentReactions',
            'userComments',
            'userFavorite'
        ])
        ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 1) + (total_views * 0.5) + (user_favorite_count * 1) DESC')
        ->take(3)
        ->get();

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
            'video_url' => 'required|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/flv,video/mpeg,video/mpg,video/m4v,video/webm,video/mkv',
            'thumbnail' => 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
            'source' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|url|max:255',
            'category_id' => 'required|exists:categories,id', // Add validation for category_id

        ]);

        $userId = Auth::user()->id;
        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Handle file upload
        if ($request->hasFile('video_url') && $request->hasFile('thumbnail')) {
            $videoFile = $request->file('video_url');
            $thumbnailFile = $request->file('thumbnail');

            // Generate filenames
            $videoFilename = time() . '_' . $videoFile->getClientOriginalName();
            $thumbnailFilename = time() . '_' . $thumbnailFile->getClientOriginalName();

            // Store files in public storage
            $videoPath = $videoFile->storeAs('video_content', $videoFilename, 'public');
            $thumbnailPath = $thumbnailFile->storeAs('thumbnail_video', $thumbnailFilename, 'public');

            // Save relative paths to DB
            $videoUrl = 'video_content/' . $videoFilename;
            $thumbnailUrl = 'thumbnail_video/' . $thumbnailFilename;

            // Create slug from title
            $slug = Str::slug($request->title);

            // Create new video record
            $video = ContentVideo::create([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'source' => $request->source,
                'tag' => $request->tag,
                'user_id' => $userId,
                'video_url' => $videoUrl,
                'thumbnail' => $thumbnailUrl,
                'link_youtube' => $request->link_youtube,
                'status' => 'pending',
            ]);


            // Create category content association
            CategoryContent::create([
                'category_id' => $request->category_id,
                'content_photo_id' => null,
                'content_video_id' => $video->id,
            ]);

            // Extract video metadata using MediaAnalyzer facade
            $this->extractAndSaveVideoMetadata($videoFile, $video->id);

            return response()->json([
                'message' => 'Video uploaded successfully',
                'data' => $video->load(['metadataVideo', 'categoryContents'])
            ], 201);
        }

        return response()->json([
            'message' => 'No video or thumbnail file provided'
        ], 400);
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
            ->get();
        if (!$contentVideo) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        return response()->json([
            'videos' => $contentVideo,
            'total' => $contentVideo->count()
        ]);
    }

    public function updateVideoByUser(Request $request, $id)
    {
        $video = ContentVideo::find($id);
        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'source' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|url|max:255',
            'video_url' => 'required|file|mimetypes:video/mp4,video/avi,video/mov,video/wmv,video/flv,video/mpeg,video/mpg,video/m4v,video/webm,video/mkv',
            'thumbnail' => 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
        ]);

        $slug = Str::slug($validatedData['title']);

        $video->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'source' => $validatedData['source'],
            'tag' => $validatedData['tag'],
            'link_youtube' => $validatedData['link_youtube'],
            'video_url' => $validatedData['video_url'],
            'thumbnail' => $validatedData['thumbnail'],
            'slug' => $slug,
        ]);

        return response()->json([
            'message' => 'Video updated successfully',
            'data' => $video
        ], 200);
    }


    public function getFavoriteByUser($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->count();

        $data = UserFavorite::with(['contentVideo.metadataVideo', 'contentVideo.userComments', 'contentVideo.user', 'contentVideo.categoryContents', 'contentVideo.contentReactions', 'contentVideo.contentReactions.reactionType'])
            ->where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->get();

        return response()->json(
            [
                'total' => $totalFavorites,
                'data' => $data,
            ]
        );
    }

    public function getVideoByCategory($slug)
    {
        $contentVideos = CategoryContent::with(['contentVideo.metadataVideo', 'contentVideo.userComments', 'contentVideo.user', 'category', 'contentVideo.contentReactions', 'contentVideo.userFavorite'])
            ->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->get();

        if (!$contentVideos) {
            return response()->json(['message' => 'Tidak ada konten ditemukan untuk kategori ini'], 404);
        }
        return response()->json($contentVideos);
    }
}
