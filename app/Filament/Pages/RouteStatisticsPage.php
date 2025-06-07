<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Pages\Widgets\RouteStatisticsLineChartWidget;
use App\Filament\Pages\Widgets\RouteStatisticsWidget;


class RouteStatisticsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.route-statistics-page-widget';
    protected static ?string $navigationLabel = 'Statistics';
    protected static ?string $slug = 'route-statistics-widget';
    protected static ?string $title = 'Statistic Pengunjung Website';
    protected static ?string $navigationGroup = 'Statistics';





    protected function getHeaderWidgets(): array
    {
        return [
            // You can add summary widgets here if desired
        ];
    }

    public function getWidgets(): array
    {
        return [
            ['class' => RouteStatisticsLineChartWidget::class, 'route' => '/'],
            ['class' => RouteStatisticsLineChartWidget::class, 'route' => 'gallery'],
            ['class' => RouteStatisticsLineChartWidget::class, 'route' => 'events'],
            ['class' => RouteStatisticsLineChartWidget::class, 'route' => 'contact'],
            ['class' => RouteStatisticsLineChartWidget::class, 'route' => 'member'],
            ['class' => RouteStatisticsLineChartWidget::class, 'route' => 'article'],
            ['class' => RouteStatisticsLineChartWidget::class,],
            RouteStatisticsWidget::class,

        ];
    }
}