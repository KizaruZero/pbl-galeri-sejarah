<?php

namespace App\Filament\Resources\UserCommentResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\UserComment;

class UserCommentOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Comments', UserComment::count())
                ->description('All content photos')
                ->descriptionIcon('heroicon-o-photo'),

            Stat::make('Published Comments', UserComment::where('status', 'published')->count())
                ->description('Published Comments')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Hidden Comments', UserComment::where('status', 'hidden')->count())
                ->description('Hidden Comments')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),
        ];
    }
}
