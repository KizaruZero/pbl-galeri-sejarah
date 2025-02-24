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

        // Perform full-text search on each table
        $contentPhotos = ContentPhoto::whereRaw("MATCH(title, description, note, alt_text) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->with('metadataPhoto', 'userComments', 'user') // Eager load related metadata photo
            ->get();

        $contentVideos = ContentVideo::whereRaw("MATCH(title, description, note) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->with('metadataVideo', 'userComments', 'user') // Eager load related metadata video
            ->get();

        $metadataPhotos = MetadataPhoto::whereRaw("MATCH(location, tag) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->with('contentPhoto') // Eager load related content photo
            ->get();

        $metadataVideos = MetadataVideo::whereRaw("MATCH(location, tag) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->with('contentVideo') // Eager load related content video
            ->get();

        $userComments = UserComment::whereRaw("MATCH(content) AGAINST(? IN BOOLEAN MODE)", [$query])
            ->with('contentPhoto', 'contentVideo', 'metadataPhoto', 'metadataVideo', 'userReactions') // Eager load related content photo and video
            ->get();

        // Combine all results into a single response
        $results = [
            'content_photos' => $contentPhotos,
            'content_videos' => $contentVideos,
            'metadata_photos' => $metadataPhotos,
            'metadata_videos' => $metadataVideos,
            'user_comments' => $userComments,
        ];

        return response()->json($results);
    }
}