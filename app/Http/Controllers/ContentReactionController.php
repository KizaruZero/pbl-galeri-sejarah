<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentReaction;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;

class ContentReactionController extends Controller
{
    //
    public function storePhotoReaction(Request $request, $id)
    {
        $request->validate([
            'content_photo_id' => 'required|exists:content_photos,id',
            'user_id' => 'required|exists:users,id',
            'reaction_type_id' => 'required|exists:reaction_types,id',
        ]);

        $contentPhoto = ContentPhoto::find($id);
        if (!$contentPhoto) {
            return response()->json(['message' => 'Content photo not found'], 404);
        }

        $contentReaction = ContentReaction::create([
            'content_photo_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $request->reaction_type_id,
        ]);

        return response()->json($contentReaction);


    }

    public function storeVideoReaction(Request $request, $id)
    {
        $request->validate([
            'content_video_id' => 'required|exists:content_videos,id',
            'user_id' => 'required|exists:users,id',
            'reaction_type_id' => 'required|exists:reaction_types,id',
        ]);

        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        $contentReaction = ContentReaction::create([
            'content_video_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $request->reaction_type_id,
        ]);
    }
}
