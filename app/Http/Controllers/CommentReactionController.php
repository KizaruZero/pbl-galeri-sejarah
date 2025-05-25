<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReaction;
use App\Models\Reaction;
use Illuminate\Support\Facades\Log;

class CommentReactionController extends Controller
{
    public function store(Request $request, $commentId)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'reaction_type_id' => 'required|exists:reactions,id'
            ]);

            // Check if reaction already exists
            $existingReaction = UserReaction::where([
                'user_id' => $validated['user_id'],
                'comment_id' => $commentId,
                'reaction_type_id' => $validated['reaction_type_id']
            ])->first();

            if ($existingReaction) {
                return response()->json([
                    'message' => 'Reaction already exists',
                    'reaction' => $existingReaction
                ], 200);
            }

            // Create new reaction
            $reaction = UserReaction::create([
                'user_id' => $validated['user_id'],
                'comment_id' => $commentId,
                'reaction_type_id' => $validated['reaction_type_id']
            ]);

            return response()->json([
                'message' => 'Reaction added successfully',
                'reaction' => $reaction->load('reactionType')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in reaction creation: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating reaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $commentId)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'reaction_type_id' => 'required|exists:reactions,id'
            ]);

            $deleted = UserReaction::where([
                'user_id' => $validated['user_id'],
                'comment_id' => $commentId,
                'reaction_type_id' => $validated['reaction_type_id']
            ])->delete();

            if ($deleted) {
                return response()->json([
                    'message' => 'Reaction removed successfully'
                ], 200);
            }

            return response()->json([
                'message' => 'Reaction not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error in reaction deletion: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting reaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index($commentId)
    {
        $reactions = UserReaction::where('comment_id', $commentId)
            ->with(['reactionType', 'user'])
            ->get();

        return response()->json($reactions);
    }
}
