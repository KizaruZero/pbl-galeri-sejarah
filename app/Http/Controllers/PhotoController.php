<?php

namespace App\Http\Controllers;

use App\Models\ContentPhoto;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index()
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])->get();
        return response()->json($contentPhoto);
    }

    public function show($id)
    {
        $contentPhoto = ContentPhoto::with(['metadataPhoto', 'userComments', 'userComments.userReactions', 'user', 'categoryContents'])->find($id);
        return response()->json($contentPhoto);
    }
}
