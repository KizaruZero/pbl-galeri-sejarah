<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\CategoryContent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
class PhotoContentCategory extends ChartWidget
{
    protected static ?string $heading = 'Video Content by Category';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get categories with counts of video content
        $videoContentByCategory = DB::table('category_content')
            ->join('categories', 'category_content.category_id', '=', 'categories.id')
            ->whereNotNull('content_video_id')
            ->select('categories.category_name', DB::raw('COUNT(*) as count'))
            ->groupBy('categories.id', 'categories.category_name')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $videoContentByCategory->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#FF9F40',
                        '#9966FF',
                        '#4BC0C0',
                        '#36A2EB',
                        '#FF4136',
                        '#FFDC00',
                        '#2ECC40',
                        '#7FDBFF',
                        '#C9CBCF',
                    ],
                ],
            ],
            'labels' => $videoContentByCategory->pluck('category_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}

?>