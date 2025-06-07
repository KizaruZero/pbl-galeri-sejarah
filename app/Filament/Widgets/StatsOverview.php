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
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 8;

    protected static ?string $pollingInterval = null;

    public ?string $filter = null;

    protected function getStats(): array
    {
        $activeFilter = $this->filter;

        // If no filter is selected, show all-time stats
        if (!$activeFilter && !isset($this->filters['startDate'])) {
            return [
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

        switch ($activeFilter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;

            case 'week':
                $startDate = Carbon::now()->subDays(7);
                $endDate = Carbon::now();
                break;

            case 'month':
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
                break;

            case 'year':
            default:
                // Default to the start of the year
                $startDate = isset($this->filters['startDate']) ?
                    Carbon::parse($this->filters['startDate']) :
                    Carbon::now()->startOfYear();
                // Set default end date to today if not provided
                $endDate = isset($this->filters['endDate']) ?
                    Carbon::parse($this->filters['endDate']) :
                    Carbon::now();
                break;
        }

        // Calculate previous period dates
        $periodLength = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodLength);
        $previousEndDate = $startDate->copy();

        // Current period queries
        $currentPhotos = ContentPhoto::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $currentVideos = ContentVideo::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $currentUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $currentComments = UserComment::whereBetween('created_at', [$startDate, $endDate])->count();
        $currentLikes = ContentReaction::whereBetween('created_at', [$startDate, $endDate])->count();

        // Previous period queries
        $previousPhotos = ContentPhoto::where('status', 'approved')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();
        $previousVideos = ContentVideo::where('status', 'approved')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();
        $previousUsers = User::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        $previousComments = UserComment::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        $previousLikes = ContentReaction::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Calculate trends
        $calculateTrend = function ($current, $previous) {
            if ($previous === 0)
                return ['value' => 100, 'direction' => 'up'];
            $change = (($current - $previous) / $previous) * 100;
            return [
                'value' => abs(round($change, 1)),
                'direction' => $change >= 0 ? 'up' : 'down'
            ];
        };

        $photosTrend = $calculateTrend($currentPhotos, $previousPhotos);
        $videosTrend = $calculateTrend($currentVideos, $previousVideos);
        $usersTrend = $calculateTrend($currentUsers, $previousUsers);
        $commentsTrend = $calculateTrend($currentComments, $previousComments);
        $likesTrend = $calculateTrend($currentLikes, $previousLikes);

        return [
            Stat::make('Total Photos', $currentPhotos)
                ->description($photosTrend['value'] . '% ' . ($photosTrend['direction'] === 'up' ? 'increase' : 'decrease'))
                ->descriptionIcon('heroicon-m-arrow-trending-' . $photosTrend['direction'])
                ->color($photosTrend['direction'] === 'up' ? 'success' : 'danger')
                ->icon('heroicon-o-camera'),

            Stat::make('Total Videos', $currentVideos)
                ->description($videosTrend['value'] . '% ' . ($videosTrend['direction'] === 'up' ? 'increase' : 'decrease'))
                ->descriptionIcon('heroicon-m-arrow-trending-' . $videosTrend['direction'])
                ->color($videosTrend['direction'] === 'up' ? 'success' : 'danger')
                ->icon('heroicon-o-film'),

            Stat::make('Total Categories', Category::count())
                ->icon('heroicon-o-tag'),

            Stat::make('Total Users', $currentUsers)
                ->description($usersTrend['value'] . '% ' . ($usersTrend['direction'] === 'up' ? 'increase' : 'decrease'))
                ->descriptionIcon('heroicon-m-arrow-trending-' . $usersTrend['direction'])
                ->color($usersTrend['direction'] === 'up' ? 'success' : 'danger')
                ->icon('heroicon-o-user-group'),

            Stat::make('Total Comments', $currentComments)
                ->description($commentsTrend['value'] . '% ' . ($commentsTrend['direction'] === 'up' ? 'increase' : 'decrease'))
                ->descriptionIcon('heroicon-m-arrow-trending-' . $commentsTrend['direction'])
                ->color($commentsTrend['direction'] === 'up' ? 'success' : 'danger')
                ->icon('heroicon-o-chat-bubble-bottom-center-text'),

            Stat::make('Total Content Likes', $currentLikes)
                ->description($likesTrend['value'] . '% ' . ($likesTrend['direction'] === 'up' ? 'increase' : 'decrease'))
                ->descriptionIcon('heroicon-m-arrow-trending-' . $likesTrend['direction'])
                ->color($likesTrend['direction'] === 'up' ? 'success' : 'danger')
                ->icon('heroicon-o-hand-thumb-up'),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}
