<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\UserFavorite;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
class UserFavoriteController extends Controller
{
    //
    public function getTotalFavorites($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->count();
        return response()->json([
            'total' => $totalFavorites,
        ]);
    }
    public function getPhotoFavoriteByUser($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->whereNotNull('content_photo_id')
            ->count();
        $data = UserFavorite::with(['contentPhoto.metadataPhoto', 'contentPhoto.userComments', 'contentPhoto.user', 'contentPhoto.categoryContents'])
            ->where('user_id', $userId)
            ->whereNotNull('content_photo_id')
            ->get();

        return response()->json([
            'total' => $totalFavorites,
            'data' => $data,
        ]);
    }
    public function getVideoFavoriteByUser($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->count();
        $data = UserFavorite::with(['contentVideo.metadataVideo', 'contentVideo.userComments', 'contentVideo.user', 'contentVideo.categoryContents'])
            ->where('user_id', $userId)
            ->whereNotNull('content_video_id')
            ->get();

        return response()->json([
            'total' => $totalFavorites,
            'data' => $data,
        ]);
    }

    public function CreatePhotoFavorite(Request $request)
    {
        $favorite = UserFavorite::create([
            'user_id' => $request->user_id,
            'content_photo_id' => $request->content_photo_id,
        ]);
        return response()->json($favorite);
    }
    public function CreateVideoFavorite(Request $request)
    {
        $favorite = UserFavorite::create([
            'user_id' => $request->user_id,
            'content_video_id' => $request->content_video_id,
        ]);
        return response()->json($favorite);
    }
    public function DeletePhotoFavorite(Request $request)
    {
        $favorite = UserFavorite::where('user_id', $request->user_id)->where('content_photo_id', $request->content_photo_id)->delete();
        return response()->json($favorite);
    }
    public function DeleteVideoFavorite(Request $request)
    {
        $favorite = UserFavorite::where('user_id', $request->user_id)->where('content_video_id', $request->content_video_id)->delete();
        return response()->json($favorite);
    }
}
