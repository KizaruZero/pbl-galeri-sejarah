<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\CategoryContent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VideoContentCategory extends ChartWidget
{
    protected static ?string $heading = 'Photo Content by Category';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // Get categories with counts of photo content
        $photoContentByCategory = DB::table('category_content')
            ->join('categories', 'category_content.category_id', '=', 'categories.id')
            ->whereNotNull('content_photo_id')
            ->select('categories.category_name', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.id', 'categories.category_name')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $photoContentByCategory->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#C9CBCF',
                        '#7FDBFF',
                        '#2ECC40',
                        '#FFDC00',
                        '#FF4136',
                    ],
                ],
            ],
            'labels' => $photoContentByCategory->pluck('category_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}