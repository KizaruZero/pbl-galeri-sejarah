<?php

namespace App\Http\Controllers;

use App\Models\ContentPhoto;
use App\Models\UserFavorite;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index()
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->get();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        return response()->json($contentPhoto);
    }

    public function show($slug)
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('slug', $slug)
            ->first();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        $contentPhoto->updateTotalViews();
        return response()->json($contentPhoto);
    }
    public function getPhotoByUser($userId)
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('user_id', $userId)
            ->get();
        if (!$contentPhoto) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        $total = $contentPhoto->count();
        return response()->json([
            'total' => $total,
            'data' => $contentPhoto,
        ]);
    }

    public function getFavoriteByUser($userId)
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
}
