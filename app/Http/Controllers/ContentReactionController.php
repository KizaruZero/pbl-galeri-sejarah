<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentReaction;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Notifications\PhotoLikes;
use App\Notifications\VideoLikes;
use App\Models\User;
use App\Models\Reaction;

class ContentReactionController extends Controller
{
    //
    public function storePhotoReaction(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reaction_type_id' => 'required|exists:reactions,id',
        ]);

        $contentPhoto = ContentPhoto::find($id);
        if (!$contentPhoto) {
            return response()->json(['message' => 'Content photo not found'], 404);
        }

        // Get the user who posted the photo
        $photoOwner = $contentPhoto->user;
        if (!$photoOwner) {
            return response()->json(['message' => 'Photo owner not found'], 404);
        }

        // Get the user who is reacting
        $reactingUser = User::find($request->user_id);
        if (!$reactingUser) {
            return response()->json(['message' => 'Reacting user not found'], 404);
        }

        // Get reaction type
        $reaction = Reaction::find($request->reaction_type_id);
        if (!$reaction) {
            return response()->json(['message' => 'Reaction type not found'], 404);
        }

        // Only notify if the reacting user is not the photo owner
        if ($photoOwner->id !== $reactingUser->id) {
            $photoOwner->notify(new PhotoLikes(
                $contentPhoto->title,
                $reaction->react_type,
                $reactingUser
            ));
        }

        $contentReaction = ContentReaction::create([
            'content_photo_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $request->reaction_type_id,
        ]);

        return response()->json($contentReaction);
    }

    public function deletePhotoReaction(Request $request, $id)
    {
        $contentPhoto = ContentPhoto::find($id);
        if (!$contentPhoto) {
            return response()->json(['message' => 'Content photo not found'], 404);
        }

        $contentReaction = ContentReaction::where('content_photo_id', $id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$contentReaction) {
            return response()->json(['message' => 'Content reaction not found'], 404);
        }

        $contentReaction->delete();
        return response()->json(['message' => 'Content reaction deleted']);
    }

    public function storeVideoReaction(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reaction_type_id' => 'required|exists:reactions,id',
        ]);

        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        // Get the user who posted the video
        $videoOwner = $contentVideo->user;
        if (!$videoOwner) {
            return response()->json(['message' => 'Video owner not found'], 404);
        }

        // Get the user who is reacting
        $reactingUser = User::find($request->user_id);
        if (!$reactingUser) {
            return response()->json(['message' => 'Reacting user not found'], 404);
        }

        // Get reaction type
        $reaction = Reaction::find($request->reaction_type_id);
        if (!$reaction) {
            return response()->json(['message' => 'Reaction type not found'], 404);
        }

        // Only notify if the reacting user is not the video owner
        if ($videoOwner->id !== $reactingUser->id) {
            $videoOwner->notify(new VideoLikes(
                $contentVideo->title,
                $reaction->react_type,
                $reactingUser
            ));
        }

        $contentReaction = ContentReaction::create([
            'content_video_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $request->reaction_type_id,
        ]);

        return response()->json($contentReaction);
    }

    public function deleteVideoReaction(Request $request, $id)
    {
        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        $contentReaction = ContentReaction::where('content_video_id', $id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$contentReaction) {
            return response()->json(['message' => 'Content reaction not found'], 404);
        }

        $contentReaction->delete();
        return response()->json(['message' => 'Content reaction deleted']);
    }
}
