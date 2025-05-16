<?php

namespace App\Http\Controllers;

use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([
                'photos' => [],
                'videos' => []
            ]);
        }

        // Search in ContentPhoto with related metadata and categories
        $photos = ContentPhoto::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('alt_text', 'like', "%{$query}%")
                ->orWhere('tag', 'like', "%{$query}%")
                ->orWhere('note', 'like', "%{$query}%")
                ->orWhereHas('metadataPhoto', function ($q) use ($query) {
                    $q->where('location', 'like', "%{$query}%")
                        ->orWhere('model', 'like', "%{$query}%");
                })
                ->orWhereHas('categoryContents.category', function ($q) use ($query) {
                    $q->where('category_name', 'like', "%{$query}%")
                        ->orWhere('category_description', 'like', "%{$query}%");
                });
        })
            ->with(['metadataPhoto', 'categoryContents.category'])
            ->where('status', 'approved')
            ->get();

        // Search in ContentVideo with related metadata and categories
        $videos = ContentVideo::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->orWhere('tag', 'like', "%{$query}%")
                ->orWhere('note', 'like', "%{$query}%")
                ->orWhereHas('metadataVideo', function ($q) use ($query) {
                    $q->where('location', 'like', "%{$query}%")
                        ->orWhere('codec_video_audio', 'like', "%{$query}%");
                })
                ->orWhereHas('categoryContents.category', function ($q) use ($query) {
                    $q->where('category_name', 'like', "%{$query}%")
                        ->orWhere('category_description', 'like', "%{$query}%");
                });
        })
            ->with(['metadataVideo', 'categoryContents.category'])
            ->where('status', 'approved')
            ->get();

        return response()->json([
            'photos' => $photos,
            'videos' => $videos
        ]);
    }
}