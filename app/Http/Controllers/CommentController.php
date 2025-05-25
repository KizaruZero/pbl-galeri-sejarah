<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserComment;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\User;
use App\Notifications\PhotoComment;
use App\Notifications\VideoComment;
class CommentController extends Controller
{
    //
    public function getCommentByContentPhoto(Request $request, $id)
    {
        // $request->validate([
        //     'content_photo_id' => 'required|exists:content_photo,id',
        // ]);

        $contentPhoto = ContentPhoto::find($id);
        if (!$contentPhoto) {
            return response()->json(['message' => 'Content photo not found'], 404);
        }

        $comments = UserComment::where('content_photo_id', $id)->with('userReactions', 'userReactions.reactionType')->get();
        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    public function getCommentByContentVideo(Request $request, $id)
    {
        // $request->validate([
        //     'content_video_id' => 'required|exists:content_videos,id',
        // ]);

        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        $comments = UserComment::where('content_video_id', $id)->with('userReactions', 'userReactions.reactionType')->get();
        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }
    public function storePhotoComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'content_photo_id' => 'required|exists:content_photo,id',
        ]);

        $contentPhoto = ContentPhoto::find($id);
        if (!$contentPhoto) {
            return response()->json(['message' => 'Content photo not found'], 404);
        }

        $photoOwner = $contentPhoto->user;
        if (!$photoOwner) {
            return response()->json(['message' => 'Photo owner not found'], 404);
        }

        $reactingUser = User::find($request->user_id);
        if (!$reactingUser) {
            return response()->json(['message' => 'Reacting user not found'], 404);
        }

        if ($photoOwner->id !== $reactingUser->id) {
            $photoOwner->notify(new PhotoComment($contentPhoto->title, $request->content, $reactingUser));
        }

        $comment = UserComment::create([
            'content' => $request->content,
            'content_photo_id' => $id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($comment);
    }

    public function destroyPhotoComment(Request $request, $id)
    {
        $comment = UserComment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function storeVideoComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'content_video_id' => 'required|exists:content_video,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $contentVideo = ContentVideo::find($id);
        if (!$contentVideo) {
            return response()->json(['message' => 'Content video not found'], 404);
        }

        $videoOwner = $contentVideo->user;
        if (!$videoOwner) {
            return response()->json(['message' => 'Video owner not found'], 404);
        }

        $reactingUser = User::find($request->user_id);
        if (!$reactingUser) {
            return response()->json(['message' => 'Reacting user not found'], 404);
        }

        if ($videoOwner->id !== $reactingUser->id) {
            $videoOwner->notify(new VideoComment($contentVideo->title, $request->content, $reactingUser));
        }

        $comment = UserComment::create([
            'content' => $request->content,
            'content_video_id' => $id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($comment);
    }

    public function destroyVideoComment(Request $request, $id)
    {
        $comment = UserComment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}