<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentVideo;

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
}
