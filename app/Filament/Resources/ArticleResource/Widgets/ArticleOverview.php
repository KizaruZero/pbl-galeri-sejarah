<?php
namespace App\Filament\Resources\ArticleResource\Widgets;

use App\Models\Article;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArticleOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userRoles = auth()->user()->roles->pluck('name');
        $query = Article::query();

        // If user is not super_admin, only show their articles
        if (!$userRoles->contains('super_admin')) {
            $query->where('user_id', auth()->id());
        }

        return [
            Stat::make('Total Article', $query->count())
                ->description('All content Article')
                ->descriptionIcon('heroicon-o-photo'),

            Stat::make('Published Article', (clone $query)->where('status', 'published')->count())
                ->description('Published Article')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Draft Article', (clone $query)->where('status', 'draft')->count())
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Archived Article', (clone $query)->where('status', 'archived')->count())
                ->description('Archived Article')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}

?>