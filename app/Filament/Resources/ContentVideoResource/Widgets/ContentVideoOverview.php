<?php
namespace App\Filament\Resources\ContentVideoResource\Widgets;

use App\Models\ContentVideo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentVideoOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Content', ContentVideo::count())
                ->description('All content photos')
                ->descriptionIcon('heroicon-o-photo'),

            Stat::make('Approved Content', ContentVideo::where('status', 'approved')->count())
                ->description('Approved photos')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Pending Content', ContentVideo::where('status', 'pending')->count())
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Rejected Content', ContentVideo::where('status', 'rejected')->count())
                ->description('Rejected photos')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}

?>