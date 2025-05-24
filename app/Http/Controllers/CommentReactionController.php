<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReaction;


class CommentReactionController extends Controller
{
    //
    public function store(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reaction_type_id' => 'required|exists:reaction_types,id',
        ]);

        $commentReaction = UserReaction::create($request->all());
        return response()->json($commentReaction);
    }

    public function delete(Request $request, $id)
    {
        $commentReaction = UserReaction::find($id);
        if (!$commentReaction) {
            return response()->json(['message' => 'Comment reaction not found'], 404);
        }

        $commentReaction->delete();

        return response()->json(['message' => 'Comment reaction deleted']);
    }
}
