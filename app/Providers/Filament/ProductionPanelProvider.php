<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Production\Pages\ProductionDashboard;
use App\Support\Filament\PodPanel;
use Filament\Panel;
use Filament\PanelProvider;

class ProductionPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('production')
            ->path('production')
            ->login()
            ->brandName('PrintFlow Production')
            ->colors(PodPanel::colors())
            ->discoverResources(in: app_path('Filament/Production/Resources'), for: 'App\\Filament\\Production\\Resources')
            ->discoverPages(in: app_path('Filament/Production/Pages'), for: 'App\\Filament\\Production\\Pages')
            ->pages([
                ProductionDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Production/Widgets'), for: 'App\\Filament\\Production\\Widgets')
            ->middleware(PodPanel::tenantMiddleware())
            ->authMiddleware(PodPanel::authMiddleware());
    }
}
