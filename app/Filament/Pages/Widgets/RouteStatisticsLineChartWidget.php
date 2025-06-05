<?php

namespace App\Filament\Pages\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\RouteStatistics;
use Carbon\Carbon;

class RouteStatisticsLineChartWidget extends LineChartWidget
{
    public ?string $filter = 'today';
    public ?string $route = '/'; // Default to Home, can be set by the widget instantiation

    public function getHeading(): string
    {
        $labels = [
            '/' => 'Home',
            'gallery' => 'Gallery',
            'events' => 'Events',
            'contact' => 'Contact',
            'member' => 'Member',
            'article' => 'Article',
        ];

        return $labels[$this->route] ?? ucfirst($this->route) . ' Statistics';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter ?? 'today';
        $route = $this->route ?? '/';

        switch ($activeFilter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                $trend = RouteStatistics::where('route', $route)
                    ->whereDate('date', $startDate)
                    ->selectRaw('HOUR(date) as period, SUM(counter) as total')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                $labels = range(0, 23);
                $data = array_fill(0, 24, 0);
                foreach ($trend as $row) {
                    $data[intval($row->period)] = $row->total;
                }
                $labels = array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ":00", $labels);
                break;

            case 'week':
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $trend = RouteStatistics::where('route', $route)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->selectRaw('DATE(date) as period, SUM(counter) as total')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                $labels = [];
                $data = [];
                for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                    $labels[] = $date->format('Y-m-d');
                    $data[] = 0;
                }
                foreach ($trend as $row) {
                    $index = array_search($row->period, $labels);
                    if ($index !== false) {
                        $data[$index] = $row->total;
                    }
                }
                break;

            case 'month':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $trend = RouteStatistics::where('route', $route)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->selectRaw('DATE(date) as period, SUM(counter) as total')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                $labels = [];
                $data = [];
                for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                    $labels[] = $date->format('Y-m-d');
                    $data[] = 0;
                }
                foreach ($trend as $row) {
                    $index = array_search($row->period, $labels);
                    if ($index !== false) {
                        $data[$index] = $row->total;
                    }
                }
                break;

            case 'year':
            default:
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfDay();
                $trend = RouteStatistics::where('route', $route)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->selectRaw('DATE_FORMAT(date, "%Y-%m") as period, SUM(counter) as total')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
                $labels = [];
                $data = [];
                for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
                    $labels[] = $date->format('Y-m');
                    $data[] = 0;
                }
                foreach ($trend as $row) {
                    $index = array_search($row->period, $labels);
                    if ($index !== false) {
                        $data[$index] = $row->total;
                    }
                }
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $data,
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}