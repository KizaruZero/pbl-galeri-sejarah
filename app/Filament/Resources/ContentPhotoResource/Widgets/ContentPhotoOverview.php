<?php
namespace App\Filament\Resources\ContentPhotoResource\Widgets;

use App\Models\ContentPhoto;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentPhotoOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Content', ContentPhoto::count())
                ->description('All content photos')
                ->descriptionIcon('heroicon-o-photo'),

            Stat::make('Approved Content', ContentPhoto::where('status', 'approved')->count())
                ->description('Approved photos')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Pending Content', ContentPhoto::where('status', 'pending')->count())
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Rejected Content', ContentPhoto::where('status', 'rejected')->count())
                ->description('Rejected photos')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}

?>