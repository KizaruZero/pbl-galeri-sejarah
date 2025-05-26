<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReaction;
use App\Models\Reaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommentReactionController extends Controller
{
    public function store(Request $request, $commentId)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'reaction_type_id' => 'required|exists:reactions,id'
            ]);

            // Delete any existing reaction from this user on this comment
            UserReaction::where([
                'user_id' => $validated['user_id'],
                'comment_id' => $commentId
            ])->delete();

            // Create new reaction
            $reaction = UserReaction::create([
                'user_id' => $validated['user_id'],
                'comment_id' => $commentId,
                'reaction_type_id' => $validated['reaction_type_id']
            ]);

            $reactionWithType = $reaction->load('reactionType');

            return response()->json([
                'message' => 'Reaction added successfully',
                'reaction' => [
                    'id' => $reactionWithType->id,
                    'reaction_type_id' => $reactionWithType->reaction_type_id,
                    'react_type' => $reactionWithType->reactionType->react_type,
                    'icon' => $reactionWithType->reactionType->icon,
                    'count' => 1,
                    'userReacted' => true
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in reaction creation: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating reaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $commentId)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'reaction_type_id' => 'required|exists:reactions,id'
            ]);

            // Find existing reaction
            $existingReaction = UserReaction::where([
                'user_id' => $validated['user_id'],
                'comment_id' => $commentId
            ])->firstOrFail();

            // Update reaction type
            $existingReaction->update([
                'reaction_type_id' => $validated['reaction_type_id']
            ]);

            return response()->json([
                'message' => 'Reaction updated successfully',
                'reaction' => $existingReaction->load('reactionType')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in reaction update: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating reaction',
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
        try {
            $userId = request()->query('user_id', 0);
            
            $reactions = UserReaction::where('comment_id', $commentId)
                ->with('reactionType')
                ->get()
                ->groupBy('reaction_type_id')
                ->map(function ($group) use ($userId) {
                    $first = $group->first();
                    return [
                        'reaction_type_id' => $first->reaction_type_id,
                        'react_type' => $first->reactionType->react_type,
                        'icon' => $first->reactionType->icon,
                        'count' => $group->count(),
                        'userReacted' => $group->contains('user_id', $userId)
                    ];
                })
                ->values();

            return response()->json($reactions);
        } catch (\Exception $e) {
            Log::error('Error fetching reactions: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching reactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
