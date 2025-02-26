<?php

namespace App\Http\Controllers;

use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\MetadataPhoto;
use App\Models\MetadataVideo;
use App\Models\UserComment;


class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Validate the search query
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->input('query');
        $contentPhotoIds = [];
        $contentVideoIds = [];

        // Search directly in content tables
        $contentPhotoIds = ContentPhoto::whereRaw("MATCH(title, description, note, alt_text) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->pluck('id')
            ->toArray();

        $contentVideoIds = ContentVideo::whereRaw("MATCH(title, description, note) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->pluck('id')
            ->toArray();

        // Search in metadata and get related content IDs
        $metadataPhotoIds = MetadataPhoto::whereRaw("MATCH(location, tag) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->pluck('content_photo_id')
            ->toArray();

        $metadataVideoIds = MetadataVideo::whereRaw("MATCH(location, tag) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->pluck('content_video_id')
            ->toArray();

        // Search in comments and get related content IDs
        $commentPhotoIds = UserComment::whereRaw("MATCH(content) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->where('content_photo_id', '!=', null)
            ->pluck('content_photo_id')
            ->toArray();

        $commentVideoIds = UserComment::whereRaw("MATCH(content) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->where('content_video_id', '!=', null)
            ->pluck('content_video_id')
            ->toArray();

        // Merge all content photo IDs and remove duplicates
        $allContentPhotoIds = array_unique(array_merge(
            $contentPhotoIds,
            $metadataPhotoIds,
            $commentPhotoIds
        ));

        // Merge all content video IDs and remove duplicates
        $allContentVideoIds = array_unique(array_merge(
            $contentVideoIds,
            $metadataVideoIds,
            $commentVideoIds
        ));

        // Get the actual content objects with their relationships
        $contentPhotos = [];
        $contentVideos = [];

        if (!empty($allContentPhotoIds)) {
            $contentPhotos = ContentPhoto::whereIn('id', $allContentPhotoIds)
                ->with('metadataPhoto', 'userComments', 'user')
                ->get();
        }

        if (!empty($allContentVideoIds)) {
            $contentVideos = ContentVideo::whereIn('id', $allContentVideoIds)
                ->with('metadataVideo', 'userComments', 'user')
                ->get();
        }

        // Return only the content objects
        $results = [
            'content_photos' => $contentPhotos,
            'content_videos' => $contentVideos,
        ];

        return response()->json($results);
    }
}