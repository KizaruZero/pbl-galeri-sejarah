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
            'react_type' => 'required|string',
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

        // Get reaction type by react_type field instead of ID
        $reaction = Reaction::where('react_type', $request->react_type)->first();
        if (!$reaction) {
            return response()->json(['message' => 'Reaction type not found'], 404);
        }

        // Check if reaction already exists to prevent duplicates
        $existingReaction = ContentReaction::where([
            'content_photo_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $reaction->id
        ])->first();

        if ($existingReaction) {
            return response()->json(['message' => 'Reaction already exists'], 409);
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
            'reaction_type_id' => $reaction->id,
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
            'react_type' => 'required|string',
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

        // Get reaction type by react_type field instead of ID
        $reaction = Reaction::where('react_type', $request->react_type)->first();
        if (!$reaction) {
            return response()->json(['message' => 'Reaction type not found'], 404);
        }

        // Check if reaction already exists to prevent duplicates
        $existingReaction = ContentReaction::where([
            'content_video_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $reaction->id
        ])->first();

        if ($existingReaction) {
            return response()->json(['message' => 'Reaction already exists'], 409);
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
            'reaction_type_id' => $reaction->id,
        ]);

        return response()->json($contentReaction);
    }

    public function deleteVideoReaction(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'react_type' => 'required|string',
        ]);

        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        // Get reaction type to find the correct reaction to delete
        $reaction = Reaction::where('react_type', $request->react_type)->first();
        if (!$reaction) {
            return response()->json(['message' => 'Reaction type not found'], 404);
        }

        $contentReaction = ContentReaction::where([
            'content_video_id' => $id,
            'user_id' => $request->user_id,
            'reaction_type_id' => $reaction->id
        ])->first();

        if (!$contentReaction) {
            return response()->json(['message' => 'Content reaction not found'], 404);
        }

        $contentReaction->delete();
        return response()->json(['message' => 'Content reaction deleted']);
    }

    public function getTotalLikesUserGet($userId)
    {
        // Count reactions for user's photos
        $photoReactions = ContentReaction::whereHas('contentPhoto', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();

        // Count reactions for user's videos
        $videoReactions = ContentReaction::whereHas('contentVideo', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();

        // Total reactions received
        $totalReactions = $photoReactions + $videoReactions;

        return response()->json([
            'total' => $totalReactions,
        ]);
    }
}
