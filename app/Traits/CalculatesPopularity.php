<?php

namespace App\Traits;

trait CalculatesPopularity
{
    /**
     * Calculate popularity based on reactions, comments, and views.
     */
    public function calculatePopularity()
    {
        // Ambil total_reactions dari relasi contentReactions
        $totalReactions = $this->contentReactions()->count();

        // Ambil total_comments dari relasi userComments
        $totalComments = $this->userComments()->count();

        // Ambil total_views langsung dari kolom di tabel content_photo
        $totalViews = $this->total_views ?? 0;

        // Define weights for each factor
        $reactionWeight = 1;
        $commentWeight = 2;
        $viewWeight = 0.5;

        // Calculate popularity
        $popularity = ($totalReactions * $reactionWeight) + ($totalComments * $commentWeight) + ($totalViews * $viewWeight);

        return $popularity;
    }

    /**
     * Update the popularity column in the database.
     */
    // public function updatePopularity()
    // {
    //     $this->popularity = $this->calculatePopularity();
    //     $this->save();
    // }
}