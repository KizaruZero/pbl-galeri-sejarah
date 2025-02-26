<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\ContentPhoto;

class ContentPhotoChart extends LineChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Content Photo Data';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

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

        $trend = Trend::query(ContentPhoto::query())
            ->between(start: $startDate, end: $endDate)
            ->dateColumn('approved_at')
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Content Photos',
                    'data' => $trend->map(fn(TrendValue $value) => $value->aggregate),
                    'fill' => 'start',
                ],
            ],
            'labels' => $trend->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('Y-m-d')), // Format dates for labels
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

    protected function getType(): string
    {
        return 'line';
    }
}