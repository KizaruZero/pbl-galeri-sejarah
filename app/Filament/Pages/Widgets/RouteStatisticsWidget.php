<?php

namespace App\Filament\Pages\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\RouteStatistics;
use Carbon\Carbon;

class RouteStatisticsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $period = $this->getFilterPeriod();

        return [
            $this->getHomeStat($period),
            $this->getGalleryStat($period),
            $this->getEventsStat($period),
            $this->getContactStat($period),
            $this->getMemberStat($period),
            $this->getArticleStat($period),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            '3days' => '3 Hari',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
        ];
    }

    protected function getFilterPeriod(): array
    {
        $filter = $this->filter ?? 'today';

        return match ($filter) {
            'today' => [
                'start' => Carbon::today(),
                'end' => Carbon::tomorrow()
            ],
            '3days' => [
                'start' => Carbon::now()->subDays(3),
                'end' => Carbon::now()
            ],
            'week' => [
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek()
            ],
            'month' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth()
            ],
            default => [
                'start' => Carbon::today(),
                'end' => Carbon::tomorrow()
            ]
        };
    }

    protected function getHomeStat(array $period): Stat
    {
        $filter = $this->filter ?? 'today';
        $current = RouteStatistics::where('route', '/')->period($filter)->sum('counter');
        $previous = $this->getPreviousCount('/', $filter);

        return Stat::make('Home Page', $current)
            ->description($this->getChangeDescription($current, $previous))
            ->descriptionIcon($this->getChangeIcon($current, $previous))
            ->color($this->getChangeColor($current, $previous))
            ->chart(RouteStatistics::getTrendData('/', $filter));
    }

    protected function getGalleryStat(array $period): Stat
    {
        $filter = $this->filter ?? 'today';
        $current = RouteStatistics::where('route', 'gallery')->period($filter)->sum('counter');
        $previous = $this->getPreviousCount('gallery', $filter);

        return Stat::make('Gallery', $current)
            ->description($this->getChangeDescription($current, $previous))
            ->descriptionIcon($this->getChangeIcon($current, $previous))
            ->color($this->getChangeColor($current, $previous))
            ->chart(RouteStatistics::getTrendData('gallery', $filter));
    }

    protected function getEventsStat(array $period): Stat
    {
        $filter = $this->filter ?? 'today';
        $current = RouteStatistics::where('route', 'events')->period($filter)->sum('counter');
        $previous = $this->getPreviousCount('events', $filter);

        return Stat::make('Events', $current)
            ->description($this->getChangeDescription($current, $previous))
            ->descriptionIcon($this->getChangeIcon($current, $previous))
            ->color($this->getChangeColor($current, $previous))
            ->chart(RouteStatistics::getTrendData('events', $filter));
    }

    protected function getContactStat(array $period): Stat
    {
        $filter = $this->filter ?? 'today';
        $current = RouteStatistics::where('route', 'contact')->period($filter)->sum('counter');
        $previous = $this->getPreviousCount('contact', $filter);

        return Stat::make('Contact', $current)
            ->description($this->getChangeDescription($current, $previous))
            ->descriptionIcon($this->getChangeIcon($current, $previous))
            ->color($this->getChangeColor($current, $previous))
            ->chart(RouteStatistics::getTrendData('contact', $filter));
    }

    protected function getMemberStat(array $period): Stat
    {
        $filter = $this->filter ?? 'today';
        $current = RouteStatistics::where('route', 'member')->period($filter)->sum('counter');
        $previous = $this->getPreviousCount('member', $filter);

        return Stat::make('Member', $current)
            ->description($this->getChangeDescription($current, $previous))
            ->descriptionIcon($this->getChangeIcon($current, $previous))
            ->color($this->getChangeColor($current, $previous))
            ->chart(RouteStatistics::getTrendData('member', $filter));
    }

    protected function getArticleStat(array $period): Stat
    {
        $filter = $this->filter ?? 'today';
        $current = RouteStatistics::where('route', 'article')->period($filter)->sum('counter');
        $previous = $this->getPreviousCount('article', $filter);

        return Stat::make('Article', $current)
            ->description($this->getChangeDescription($current, $previous))
            ->descriptionIcon($this->getChangeIcon($current, $previous))
            ->color($this->getChangeColor($current, $previous))
            ->chart(RouteStatistics::getTrendData('article', $filter));
    }

    protected function getPreviousCount(string $route, string $filter): int
    {
        $previousPeriod = $this->getPreviousFilter($filter);

        return RouteStatistics::where('route', $route)
            ->period($previousPeriod)
            ->sum('counter');
    }

    protected function getPreviousFilter(string $filter): string
    {
        return match ($filter) {
            'today' => 'yesterday',
            '3days' => 'previous_3days',
            'week' => 'previous_week',
            'month' => 'previous_month',
            default => 'yesterday'
        };
    }

    protected function getChangeDescription(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'Meningkat dari 0' : 'Tidak ada perubahan';
        }

        $change = $current - $previous;
        $percentage = round(($change / $previous) * 100, 1);

        if ($change > 0) {
            return "Meningkat {$percentage}% dari periode sebelumnya";
        } elseif ($change < 0) {
            return "Menurun " . abs($percentage) . "% dari periode sebelumnya";
        } else {
            return "Tidak ada perubahan dari periode sebelumnya";
        }
    }

    protected function getChangeIcon(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus';
        }

        $change = $current - $previous;

        if ($change > 0) {
            return 'heroicon-m-arrow-trending-up';
        } elseif ($change < 0) {
            return 'heroicon-m-arrow-trending-down';
        } else {
            return 'heroicon-m-minus';
        }
    }

    protected function getChangeColor(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current > 0 ? 'success' : 'gray';
        }

        $change = $current - $previous;

        if ($change > 0) {
            return 'success';
        } elseif ($change < 0) {
            return 'danger';
        } else {
            return 'gray';
        }
    }
}