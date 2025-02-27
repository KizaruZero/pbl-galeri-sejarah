<?php

namespace App\Traits;

trait CalculatesPopularity
{
    /**
     * Calculate popularity based on reactions, comments, and views.
     */
    public function calculatePopularity()
    {
        $totalReactions = $this->contentReactions()->count();
        $totalComments = $this->userComments()->count();
        $totalViews = $this->metadata->views ?? 0; // Assuming views are stored in metadata

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
    public function updatePopularity()
    {
        $this->popularity = $this->calculatePopularity();
        $this->save();
    }
}