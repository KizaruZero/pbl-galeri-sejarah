<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    //
    public function index()
    {
        $article = Article::where('status', 'published')->get();
        if ($article->isEmpty()) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        return response()->json($article);
    }

    public function show($slug)
    {
        $article = Article::where('status', 'published')
            ->where('slug', $slug)
            ->first();
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        $article->updateTotalViews();
        return response()->json($article);
    }

    public function getPopularArticle()
    {
        $article = Article::where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(4)
            ->get();

        if ($article->isEmpty()) {
            return response()->json(['message' => 'No popular articles found'], 404);
        }

        return response()->json($article);
    }
}
