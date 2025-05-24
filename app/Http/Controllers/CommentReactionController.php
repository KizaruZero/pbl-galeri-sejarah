<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReaction;
use App\Models\Reaction;
use Illuminate\Support\Facades\Log;

class CommentReactionController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            Log::info('Reaction request received', $request->all());
            
            // Validate reaction type exists
            $reactionType = Reaction::find($request->reaction_type_id);
            if (!$reactionType) {
                return response()->json(['message' => 'Invalid reaction type'], 400);
            }

            // Check existing reaction
            $existingReaction = UserReaction::where([
                'user_id' => $request->user_id,
                'comment_id' => $id
            ])->first();

            if ($existingReaction) {
                if ($existingReaction->reaction_type_id == $request->reaction_type_id) {
                    $existingReaction->delete();
                    return response()->json(['message' => 'Reaction removed']);
                }
                
                $existingReaction->reaction_type_id = $request->reaction_type_id;
                $existingReaction->save();
                return response()->json($existingReaction);
            }

            // Create new reaction
            $commentReaction = new UserReaction();
            $commentReaction->user_id = $request->user_id;
            $commentReaction->comment_id = $id;
            $commentReaction->reaction_type_id = $request->reaction_type_id;
            $commentReaction->save();

            Log::info('Reaction created successfully', ['reaction' => $commentReaction]);
            return response()->json($commentReaction);

        } catch (\Exception $e) {
            Log::error('Error in reaction creation: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error creating reaction',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function getReactions($commentId)
    {
        $reactions = UserReaction::where('comment_id', $commentId)
            ->with(['reactionType', 'user'])
            ->get();

        return response()->json($reactions);
    }
}
