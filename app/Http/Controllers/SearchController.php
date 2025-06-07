<?php

namespace App\Http\Controllers;

use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');
        $useFuzzy = $request->input('use_fuzzy', true);

        // If no search criteria, return just categories
        if (empty($query) && (empty($category) || $category === 'All categories')) {
            return response()->json([
                'photos' => [],
                'videos' => [],
                'photo_count' => 0,
                'video_count' => 0,
                'total_count' => 0,
                'categories' => Category::pluck('category_name')->toArray(),
                'search_data' => []
            ]);
        }

        // Get all data for fuzzy search
        $allPhotos = $this->getPhotosForSearch($category);
        $allVideos = $this->getVideosForSearch($category);

        // If no query but category selected, return filtered results
        if (empty($query)) {
            $formattedPhotos = $this->formatPhotoResults($allPhotos);
            $formattedVideos = $this->formatVideoResults($allVideos);

            return response()->json([
                'photos' => $formattedPhotos,
                'videos' => $formattedVideos,
                'photo_count' => count($formattedPhotos),
                'video_count' => count($formattedVideos),
                'total_count' => count($formattedPhotos) + count($formattedVideos),
                'categories' => Category::pluck('category_name')->toArray(),
                'search_data' => $this->prepareSearchData($allPhotos, $allVideos)
            ]);
        }

        // Prepare data for Fuse.js (client-side fuzzy search)
        $searchData = $this->prepareSearchData($allPhotos, $allVideos);

        // Also perform server-side search as fallback
        $serverResults = $this->performServerSearch($query, $category);

        return response()->json([
            'photos' => $serverResults['photos'],
            'videos' => $serverResults['videos'],
            'photo_count' => count($serverResults['photos']),
            'video_count' => count($serverResults['videos']),
            'total_count' => count($serverResults['photos']) + count($serverResults['videos']),
            'categories' => Category::pluck('category_name')->toArray(),
            'search_data' => $searchData,
            'query' => $query,
            'use_fuzzy' => $useFuzzy
        ]);
    }

    private function getPhotosForSearch($category = null)
    {
        $photoQuery = ContentPhoto::where('status', 'approved')
            ->with(['metadataPhoto', 'categoryContents.category', 'user']);

        if (!empty($category) && $category !== 'All categories') {
            $photoQuery->whereHas('categoryContents.category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        return $photoQuery->orderBy('created_at', 'desc')->get();
    }

    private function getVideosForSearch($category = null)
    {
        $videoQuery = ContentVideo::where('status', 'approved')
            ->with(['metadataVideo', 'categoryContents.category', 'user']);

        if (!empty($category) && $category !== 'All categories') {
            $videoQuery->whereHas('categoryContents.category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        return $videoQuery->orderBy('created_at', 'desc')->get();
    }

    private function prepareSearchData($photos, $videos)
    {
        $searchData = [];

        // Prepare photo data for Fuse.js
        foreach ($photos as $photo) {
            $searchData[] = [
                'id' => $photo->id,
                'type' => 'photo',
                'title' => $photo->title ?? '',
                'slug' => $photo->slug ?? '',
                'description' => $photo->description ?? '',
                'alt_text' => $photo->alt_text ?? '',
                'tag' => $photo->tag ?? '',
                'note' => $photo->note ?? '',
                'source' => $photo->source ?? '',
                'user_name' => $photo->user->name ?? '',
                'user_email' => $photo->user->email ?? '',

                // Metadata fields
                'location' => $photo->metadataPhoto->location ?? '',
                'model' => $photo->metadataPhoto->model ?? '',
                'aperture' => $photo->metadataPhoto->aperture ?? '',
                'iso' => $photo->metadataPhoto->ISO ?? '',
                'dimensions' => $photo->metadataPhoto->dimensions ?? '',
                'collection_date' => $photo->metadataPhoto->collection_date ?? '',
                'file_size' => $photo->metadataPhoto->file_size ?? '',

                // Category
                'category' => $photo->categoryContents->isEmpty() ? 'Uncategorized' : $photo->categoryContents->first()->category->category_name,
                'category_slug' => $photo->categoryContents->isEmpty() ? 'uncategorized' : $photo->categoryContents->first()->category->slug,

                // Additional searchable fields
                'combined_metadata' => $this->getCombinedMetadata($photo->metadataPhoto),
                'all_tags' => $this->extractAllTags($photo->tag),

                // Display data
                'image_url' => asset('storage/' . $photo->image_url),
                'url' => route('photo.show', $photo->slug),
                'created_at' => $photo->created_at->format('Y-m-d'),
                'created_at_human' => $photo->created_at->diffForHumans(),
                'total_views' => $photo->total_views ?? 0
            ];
        }

        // Prepare video data for Fuse.js
        foreach ($videos as $video) {
            $searchData[] = [
                'id' => $video->id,
                'type' => 'video',
                'title' => $video->title ?? '',
                'slug' => $video->slug ?? '',
                'description' => $video->description ?? '',
                'tag' => $video->tag ?? '',
                'note' => $video->note ?? '',
                'user_name' => $video->user->name ?? '',
                'user_email' => $video->user->email ?? '',

                // Video metadata fields
                'location' => $video->metadataVideo->location ?? '',
                'codec_video_audio' => $video->metadataVideo->codec_video_audio ?? '',
                'duration' => $video->metadataVideo->duration ?? '',
                'resolution' => $video->metadataVideo->resolution ?? '',
                'frame_rate' => $video->metadataVideo->frame_rate ?? '',
                'file_size' => $video->metadataVideo->file_size ?? '',

                // Category
                'category' => $video->categoryContents->isEmpty() ? 'Uncategorized' : $video->categoryContents->first()->category->category_name,
                'category_slug' => $video->categoryContents->isEmpty() ? 'uncategorized' : $video->categoryContents->first()->category->slug,

                // Additional searchable fields
                'combined_metadata' => $this->getCombinedVideoMetadata($video->metadataVideo),
                'all_tags' => $this->extractAllTags($video->tag),

                // Display data
                'image_url' => asset('storage/' . $video->thumbnail),
                'url' => route('video.show', $video->slug),
                'created_at' => $video->created_at->format('Y-m-d'),
                'created_at_human' => $video->created_at->diffForHumans(),
                'total_views' => $video->total_views ?? 0,
                'duration_formatted' => $this->formatDuration($video->metadataVideo->duration ?? '')
            ];
        }

        return $searchData;
    }

    private function formatDuration($duration)
    {
        if (!$duration)
            return '';

        // If duration is in seconds
        if (is_numeric($duration)) {
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;
            return sprintf('%d:%02d', $minutes, $seconds);
        }

        return $duration;
    }

    private function getCombinedMetadata($metadata)
    {
        if (!$metadata)
            return '';

        $combined = [];
        if ($metadata->location)
            $combined[] = $metadata->location;
        if ($metadata->model)
            $combined[] = $metadata->model;
        if ($metadata->aperture)
            $combined[] = "f/" . $metadata->aperture;
        if ($metadata->ISO)
            $combined[] = "ISO " . $metadata->ISO;
        if ($metadata->dimensions)
            $combined[] = $metadata->dimensions;

        return implode(' ', $combined);
    }

    private function getCombinedVideoMetadata($metadata)
    {
        if (!$metadata)
            return '';

        $combined = [];
        if ($metadata->location)
            $combined[] = $metadata->location;
        if ($metadata->codec_video_audio)
            $combined[] = $metadata->codec_video_audio;
        if ($metadata->duration)
            $combined[] = $metadata->duration;
        if ($metadata->resolution)
            $combined[] = $metadata->resolution;
        if ($metadata->frame_rate)
            $combined[] = $metadata->frame_rate . "fps";

        return implode(' ', $combined);
    }

    private function extractAllTags($tagString)
    {
        if (!$tagString)
            return '';

        // Split by comma, semicolon, or pipe and clean up
        $tags = preg_split('/[,;|]/', $tagString);
        $cleanTags = array_map('trim', $tags);
        $cleanTags = array_filter($cleanTags);

        return implode(' ', $cleanTags);
    }

    private function performServerSearch($query, $category)
    {
        // Your existing server-side search logic as fallback
        $photoQuery = ContentPhoto::where('status', 'approved');

        if (!empty($category) && $category !== 'All categories') {
            $photoQuery->whereHas('categoryContents.category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        if (!empty($query)) {
            $photoQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('alt_text', 'like', "%{$query}%")
                    ->orWhere('tag', 'like', "%{$query}%")
                    ->orWhere('note', 'like', "%{$query}%")
                    ->orWhereHas('metadataPhoto', function ($q) use ($query) {
                        $q->where('location', 'like', "%{$query}%")
                            ->orWhere('model', 'like', "%{$query}%");
                    })
                    ->orWhereHas('user', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    });
            });
        }

        $photos = $photoQuery->with(['metadataPhoto', 'categoryContents.category', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Similar for videos
        $videoQuery = ContentVideo::where('status', 'approved');

        if (!empty($category) && $category !== 'All categories') {
            $videoQuery->whereHas('categoryContents.category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        if (!empty($query)) {
            $videoQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('tag', 'like', "%{$query}%")
                    ->orWhere('note', 'like', "%{$query}%")
                    ->orWhereHas('metadataVideo', function ($q) use ($query) {
                        $q->where('location', 'like', "%{$query}%")
                            ->orWhere('codec_video_audio', 'like', "%{$query}%");
                    })
                    ->orWhereHas('user', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    });
            });
        }

        $videos = $videoQuery->with(['metadataVideo', 'categoryContents.category', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'photos' => $this->formatPhotoResults($photos),
            'videos' => $this->formatVideoResults($videos)
        ];
    }

    private function formatPhotoResults($photos)
    {
        $formattedResults = [];

        foreach ($photos as $photo) {
            $formattedResults[] = [
                'id' => $photo->id,
                'type' => 'photo',
                'title' => $photo->title,
                'description' => $photo->description,
                'image_url' => asset('storage/' . $photo->image_url),
                'url' => route('photo.show', $photo->slug),
                'slug' => $photo->slug,
                'category' => $photo->categoryContents->isEmpty() ? 'Uncategorized' : $photo->categoryContents->first()->category->category_name,
                'category_slug' => $photo->categoryContents->isEmpty() ? 'uncategorized' : $photo->categoryContents->first()->category->slug,
                'user_name' => $photo->user->name ?? 'Unknown',
                'total_views' => $photo->total_views ?? 0,
                'created_at' => $photo->created_at->format('Y-m-d'),
                'created_at_human' => $photo->created_at->diffForHumans(),
                'metadata' => [
                    'location' => $photo->metadataPhoto->location ?? '',
                    'model' => $photo->metadataPhoto->model ?? '',
                    'aperture' => $photo->metadataPhoto->aperture ?? '',
                    'iso' => $photo->metadataPhoto->ISO ?? '',
                    'dimensions' => $photo->metadataPhoto->dimensions ?? ''
                ]
            ];
        }

        return $formattedResults;
    }

    private function formatVideoResults($videos)
    {
        $formattedResults = [];

        foreach ($videos as $video) {
            $formattedResults[] = [
                'id' => $video->id,
                'type' => 'video',
                'title' => $video->title,
                'description' => $video->description,
                'image_url' => asset('storage/' . $video->thumbnail),
                'url' => route('video.show', $video->slug),
                'slug' => $video->slug,
                'category' => $video->categoryContents->isEmpty() ? 'Uncategorized' : $video->categoryContents->first()->category->category_name,
                'category_slug' => $video->categoryContents->isEmpty() ? 'uncategorized' : $video->categoryContents->first()->category->slug,
                'user_name' => $video->user->name ?? 'Unknown',
                'total_views' => $video->total_views ?? 0,
                'created_at' => $video->created_at->format('Y-m-d'),
                'created_at_human' => $video->created_at->diffForHumans(),
                'duration_formatted' => $this->formatDuration($video->metadataVideo->duration ?? ''),
                'metadata' => [
                    'location' => $video->metadataVideo->location ?? '',
                    'codec' => $video->metadataVideo->codec_video_audio ?? '',
                    'duration' => $video->metadataVideo->duration ?? '',
                    'resolution' => $video->metadataVideo->resolution ?? '',
                    'frame_rate' => $video->metadataVideo->frame_rate ?? ''
                ]
            ];
        }

        return $formattedResults;
    }

    // Additional endpoint for getting fresh search data
    public function getSearchData(Request $request)
    {
        $category = $request->input('category');

        $photos = $this->getPhotosForSearch($category);
        $videos = $this->getVideosForSearch($category);

        return response()->json([
            'search_data' => $this->prepareSearchData($photos, $videos),
            'categories' => Category::pluck('category_name')->toArray(),
            'photos' => $this->formatPhotoResults($photos),
            'videos' => $this->formatVideoResults($videos),
            'photo_count' => count($photos),
            'video_count' => count($videos),
            'total_count' => count($photos) + count($videos)
        ]);
    }
}