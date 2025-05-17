<?php

namespace App\Http\Controllers;

use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');

        if (empty($query) && (empty($category) || $category === 'All categories')) {
            return response()->json([
                'photos' => [],
                'videos' => [],
                'categories' => Category::pluck('category_name')->toArray()
            ]);
        }

        // Search in ContentPhoto with related metadata and categories
        $photoQuery = ContentPhoto::where('status', 'approved')
            ->where(function ($q) use ($query, $category) {
                if (!empty($query)) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('alt_text', 'like', "%{$query}%")
                        ->orWhere('tag', 'like', "%{$query}%")
                        ->orWhere('note', 'like', "%{$query}%")
                        ->orWhereHas('metadataPhoto', function ($q) use ($query) {
                            $q->where('location', 'like', "%{$query}%")
                                ->orWhere('model', 'like', "%{$query}%");
                        });
                }
                
                if (!empty($category) && $category !== 'All categories') {
                    $q->whereHas('categoryContents.category', function ($q) use ($category) {
                        $q->where('category_name', $category);
                    });
                }
            })
            ->with(['metadataPhoto', 'categoryContents.category']);

        $photos = $photoQuery->get();

        // Search in ContentVideo with related metadata and categories
        $videoQuery = ContentVideo::where('status', 'approved')
            ->where(function ($q) use ($query, $category) {
                if (!empty($query)) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('tag', 'like', "%{$query}%")
                        ->orWhere('note', 'like', "%{$query}%")
                        ->orWhereHas('metadataVideo', function ($q) use ($query) {
                            $q->where('location', 'like', "%{$query}%")
                                ->orWhere('codec_video_audio', 'like', "%{$query}%");
                        });
                }
                
                if (!empty($category) && $category !== 'All categories') {
                    $q->whereHas('categoryContents.category', function ($q) use ($category) {
                        $q->where('category_name', $category);
                    });
                }
            })
            ->with(['metadataVideo', 'categoryContents.category']);

        $videos = $videoQuery->get();

        // Format results for frontend
        $formattedResults = [];
        
        foreach ($photos as $photo) {
            $formattedResults[] = [
                'id' => $photo->id,
                'type' => 'photo',
                'title' => $photo->title,
                'description' => $photo->description,
                'image_url' => asset('storage/' . $photo->image_url),
                'url' => route('photo.show', $photo->slug),
                'category' => $photo->categoryContents->isEmpty() ? 'Uncategorized' : $photo->categoryContents->first()->category->category_name
            ];
        }

        foreach ($videos as $video) {
            $formattedResults[] = [
                'id' => $video->id,
                'type' => 'video',
                'title' => $video->title,
                'description' => $video->description,
                'image_url' => asset('storage/' . $video->thumbnail),
                'url' => route('video.show', $video->slug),
                'category' => $video->categoryContents->isEmpty() ? 'Uncategorized' : $video->categoryContents->first()->category->category_name
            ];
        }

        return response()->json([
            'items' => $formattedResults,
            'categories' => Category::pluck('category_name')->toArray()
        ]);
    }
}