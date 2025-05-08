<?php
namespace App\Filament\Resources\ArticleResource\Widgets;

use App\Models\Article;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArticleOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Article', Article::count())
                ->description('All content Article')
                ->descriptionIcon('heroicon-o-photo'),

            Stat::make('Published Article', Article::where('status', 'published')->count())
                ->description('Published Article')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Draft Article', Article::where('status', 'draft')->count())
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Archived Article', Article::where('status', 'archived')->count())
                ->description('Archived Article')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}

?>