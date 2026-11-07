<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Platform\Pages\PlatformDashboard;
use App\Filament\Platform\Pages\PlatformSettings;
use App\Support\Filament\PodPanel;
use Filament\Panel;
use Filament\PanelProvider;

class PlatformPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('platform')
            ->path('platform')
            ->login()
            ->brandName('PrintFlow Platform')
            ->colors(PodPanel::colors())
            ->discoverResources(in: app_path('Filament/Platform/Resources'), for: 'App\\Filament\\Platform\\Resources')
            ->discoverPages(in: app_path('Filament/Platform/Pages'), for: 'App\\Filament\\Platform\\Pages')
            ->pages([
                PlatformDashboard::class,
                PlatformSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Platform/Widgets'), for: 'App\\Filament\\Platform\\Widgets')
            ->middleware(PodPanel::middleware())
            ->authMiddleware(PodPanel::authMiddleware());
    }
}
