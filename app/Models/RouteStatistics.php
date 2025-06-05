<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RouteStatistics extends Model
{
    protected $fillable = [
        'user_id',
        'team_id',
        'method',
        'route',
        'status',
        'ip',
        'date',
        'counter'
    ];

    protected $casts = [
        'date' => 'datetime',
        'counter' => 'integer',
        'status' => 'integer',
        'user_id' => 'integer',
        'team_id' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter berdasarkan route yang ditrack
    public function scopeTrackedRoutes($query)
    {
        return $query->whereIn('route', ['/', 'gallery', 'events', 'contact', 'member', 'article']);
    }

    // Scope untuk filter berdasarkan periode
    public function scopePeriod($query, string $period)
    {
        return match ($period) {
            'today' => $query->whereDate('date', Carbon::today()),
            '3days' => $query->where('date', '>=', Carbon::now()->subDays(3)),
            'week' => $query->whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]),
            'month' => $query->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]),
            default => $query
        };
    }

    // Method untuk mendapatkan statistik route
    public static function getRouteStats(string $period = 'today'): array
    {
        $routes = ['/', 'gallery', 'events', 'contact', 'member', 'article'];
        $stats = [];

        foreach ($routes as $route) {
            $count = self::trackedRoutes()
                ->where('route', $route)
                ->period($period)
                ->sum('counter');

            $stats[$route] = $count;
        }

        return $stats;
    }

    // Method untuk mendapatkan total kunjungan
    public static function getTotalVisits(string $period = 'today'): int
    {
        return self::trackedRoutes()
            ->period($period)
            ->sum('counter');
    }

    // Method untuk mendapatkan unique visitors
    public static function getUniqueVisitors(string $period = 'today'): int
    {
        return self::trackedRoutes()
            ->period($period)
            ->distinct('ip')
            ->count('ip');
    }

    // Method untuk mendapatkan halaman paling populer
    public static function getMostPopularRoute(string $period = 'today'): ?string
    {
        $result = self::trackedRoutes()
            ->period($period)
            ->selectRaw('route, SUM(counter) as total')
            ->groupBy('route')
            ->orderBy('total', 'desc')
            ->first();

        return $result?->route;
    }

    // Method untuk mendapatkan data chart
    public static function getChartData(string $period = 'today'): array
    {
        $routes = ['/', 'gallery', 'events', 'contact', 'member', 'article'];
        $routeLabels = [
            '/' => 'Home',
            'gallery' => 'Gallery',
            'events' => 'Events',
            'contact' => 'Contact',
            'member' => 'Member',
            'article' => 'Article'
        ];

        $data = [];
        $labels = [];

        foreach ($routes as $route) {
            $count = self::trackedRoutes()
                ->where('route', $route)
                ->period($period)
                ->sum('counter');

            $data[] = $count;
            $labels[] = $routeLabels[$route];
        }

        return [
            'data' => $data,
            'labels' => $labels
        ];
    }

    // Method untuk mendapatkan trend data (untuk chart timeline)
    public static function getTrendData(string $route, string $period = 'week'): array
    {
        $query = self::where('route', $route)->period($period);

        return match ($period) {
            'today' => $query->selectRaw('HOUR(date) as period, SUM(counter) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->pluck('total', 'period')
                ->toArray(),

            '3days', 'week' => $query->selectRaw('DATE(date) as period, SUM(counter) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->pluck('total', 'period')
                ->toArray(),

            'month' => $query->selectRaw('DATE(date) as period, SUM(counter) as total')
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->pluck('total', 'period')
                ->toArray(),

            default => []
        };
    }

    // Accessor untuk format route name
    public function getRouteNameAttribute(): string
    {
        return match ($this->route) {
            '/' => 'Home',
            'gallery' => 'Gallery',
            'events' => 'Events',
            'contact' => 'Contact',
            'member' => 'Member',
            'article' => 'Article',
            default => $this->route
        };
    }

    // Accessor untuk status badge color
    public function getStatusColorAttribute(): string
    {
        return match (true) {
            $this->status >= 200 && $this->status < 300 => 'success',
            $this->status >= 300 && $this->status < 400 => 'warning',
            $this->status >= 400 => 'danger',
            default => 'gray'
        };
    }

    // Accessor untuk route badge color
    public function getRouteColorAttribute(): string
    {
        return match ($this->route) {
            '/' => 'primary',
            'gallery' => 'success',
            'events' => 'warning',
            'contact' => 'info',
            'member' => 'secondary',
            'article' => 'danger',
            default => 'gray'
        };
    }
}