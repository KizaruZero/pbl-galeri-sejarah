<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentVideo;
use App\Models\ContentPhoto;
use App\Models\CategoryContent;
use App\Models\Article;
use Inertia\Inertia;

class FrontendController extends Controller
{
    // Gallery Route
    public function ContentByCategory($slug)
    {
        $content = CategoryContent::with([
            'contentVideo.metadataVideo',
            'contentPhoto.metadataPhoto',
            'contentPhoto.userComments',

            'contentPhoto.categoryContents',
            'category' // Eager load the category
        ])
            ->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->first();

        if (!$content) {
            return response()->json(['message' => 'Tidak ada konten ditemukan untuk kategori ini'], 404);
        }
        return view('frontend.gallery', [
            'content' => $content,
            'category' => $content->category,
        ]);
    }
}
