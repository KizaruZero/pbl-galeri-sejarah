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

        // If no search criteria, return just categories
        if (empty($query) && (empty($category) || $category === 'All categories')) {
            return response()->json([
                'items' => [],
                'categories' => Category::pluck('category_name')->toArray()
            ]);
        }

        // Base query for photos
        $photoQuery = ContentPhoto::where('status', 'approved');
        
        // Apply category filter if specified
        if (!empty($category) && $category !== 'All categories') {
            $photoQuery->whereHas('categoryContents.category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        // Apply search term filter if specified
        if (!empty($query)) {
            $photoQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('alt_text', 'like', "%{$query}%")
                    ->orWhere('tag', 'like', "%{$query}%")
                    ->orWhere('note', 'like', "%{$query}%")
                    ->orWhereHas('metadataPhoto', function ($q) use ($query) {
                        $q->where('location', 'like', "%{$query}%")
                            ->orWhere('model', 'like', "%{$query}%");
                    });
            });
        }

        $photos = $photoQuery->with(['metadataPhoto', 'categoryContents.category'])->get();

        // Base query for videos
        $videoQuery = ContentVideo::where('status', 'approved');
        
        // Apply category filter if specified
        if (!empty($category) && $category !== 'All categories') {
            $videoQuery->whereHas('categoryContents.category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        // Apply search term filter if specified
        if (!empty($query)) {
            $videoQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('tag', 'like', "%{$query}%")
                    ->orWhere('note', 'like', "%{$query}%")
                    ->orWhereHas('metadataVideo', function ($q) use ($query) {
                        $q->where('location', 'like', "%{$query}%")
                            ->orWhere('codec_video_audio', 'like', "%{$query}%");
                    });
            });
        }

        $videos = $videoQuery->with(['metadataVideo', 'categoryContents.category'])->get();

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
                'slug' => $photo->slug,
                'category' => $photo->categoryContents->isEmpty() ? 'Uncategorized' : $photo->categoryContents->first()->category->category_name,
                'category_slug' => $photo->categoryContents->isEmpty() ? 'Uncategorized' : $photo->categoryContents->first()->category->slug

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
                'slug' => $video->slug,
                'category' => $video->categoryContents->isEmpty() ? 'Uncategorized' : $video->categoryContents->first()->category->category_name,
                'category_slug' => $video->categoryContents->isEmpty() ? 'Uncategorized' : $video->categoryContents->first()->category->slug
            ];
        }

        return response()->json([
            'items' => $formattedResults,
            'categories' => Category::pluck('category_name')->toArray()
        ]);
    }
}