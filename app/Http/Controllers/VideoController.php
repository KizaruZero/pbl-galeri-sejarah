<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentVideo;

class VideoController extends Controller
{
    //
    public function index()
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])->get();
        return response()->json($contentVideo);
    }

    public function show($id)
    {
        $contentVideo = ContentVideo::with(['metadataVideo', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])->find($id);
        return response()->json($contentVideo);
    }
}
