<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentVideo;
use App\Models\UserFavorite;

class VideoController extends Controller
{
    //
    public function index()
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->get();
        if (!$contentVideo) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        return response()->json($contentVideo);
    }

    public function show($slug)
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('slug', $slug)
            ->first();
        if (!$contentVideo) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        $contentVideo->updateTotalViews();
        return response()->json($contentVideo);
    }

    public function getVideoByUser($userId)
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('user_id', $userId)
            ->get();
        if (!$contentVideo) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        $total = $contentVideo->count();
        return response()->json($total);
    }

    public function getFavoriteByUser($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->count();

        return response()->json($totalFavorites);
    }
}
