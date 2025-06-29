<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Rupadana\ApiService\ApiServicePlugin;
use App\Filament\Pages\CompanyProfileSettings;

use Filament\Enums\ThemeMode;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('dashboard')
            ->homeUrl('/')
            ->login()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path(path: 'Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\LastLoggedInUsers::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->pages([
                    // Pages\Dashboard::class,
                CompanyProfileSettings::class,
                \App\Filament\Pages\RouteStatisticsPage::class,

            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                'Photo Contents',
                'Video Contents',
                'Content Management',
                'Users',
                'Statistics'
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->databaseNotifications(true)
            ->plugins([
                ApiServicePlugin::make(),
            ]);
    }
}
