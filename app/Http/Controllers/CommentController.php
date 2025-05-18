<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserComment;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
class CommentController extends Controller
{
    //
    public function storePhotoComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'content_photo_id' => 'required|exists:content_photos,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $contentPhoto = ContentPhoto::find($id);
        if (!$contentPhoto) {
            return response()->json(['message' => 'Content photo not found'], 404);
        }

        $comment = UserComment::create([
            'content' => $request->content,
            'content_photo_id' => $id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($comment);
    }

    public function storeVideoComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'content_video_id' => 'required|exists:content_videos,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        $comment = UserComment::create([
            'content' => $request->content,
            'content_video_id' => $id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($comment);
    }
}

