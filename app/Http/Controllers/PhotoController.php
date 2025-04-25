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
        $contentPhotos = ContentPhoto::with(['metadataPhoto', 'userComments', 'user', 'categoryContents'])
            ->where('status', 'approved')
            ->where('user_id', $userId)
            ->get();
        
        if ($contentPhotos->isEmpty()) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
        
        return response()->json([
            'photos' => $contentPhotos,
            'total' => $contentPhotos->count()
        ]);
    }

    public function getFavoriteByUser($userId)
    {
        $totalFavorites = UserFavorite::where('user_id', $userId)
            ->whereNotNull('content_photo_id')
            ->count();

        return response()->json($totalFavorites);
    }
}
