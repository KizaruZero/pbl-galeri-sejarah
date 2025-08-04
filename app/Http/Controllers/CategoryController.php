<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'data' => $categories
        ]);
    }


    public function getCategoryByName($name)
    {
        $category = Category::where('category_name', $name)->first();

        if (!$category) {
            return response()->json([
                'message' => 'Kategori Tidak Ditemukan, Pastikan Kategori Yang Anda Input di Excel Sesuai Dengan Kategori Yang Ada'
            ], 404);
        }

        return response()->json($category);
    }
}
