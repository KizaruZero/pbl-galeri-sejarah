<?php

namespace App\Filament\Widgets;

use App\Models\ContentReaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Category;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserReaction;


class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 8;

    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Photos', ContentPhoto::where('status', 'approved')->count())
                ->icon('heroicon-o-camera'),
            Stat::make('Total Videos', ContentVideo::where('status', 'approved')->count())
                ->icon('heroicon-o-film'),
            Stat::make('Total Categories', Category::count())
                ->icon('heroicon-o-tag'),
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-user-group'),
            Stat::make('Total Comments', UserComment::count())
                ->icon('heroicon-o-chat-bubble-bottom-center-text'),
            Stat::make('Total Content Likes', ContentReaction::count())
                ->icon('heroicon-o-hand-thumb-up'),
        ];
    }
}
