<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Business\Pages\AiAssistant;
use App\Filament\Business\Pages\BusinessDashboard;
use App\Filament\Business\Pages\BusinessSettings;
use App\Filament\Business\Pages\ModuleMarketplace;
use App\Support\Filament\PodPanel;
use Filament\Panel;
use Filament\PanelProvider;

class BusinessPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('business')
            ->path('app')
            ->login()
            ->brandName('PrintFlow Business')
            ->colors(PodPanel::colors())
            ->discoverResources(in: app_path('Filament/Business/Resources'), for: 'App\\Filament\\Business\\Resources')
            ->discoverPages(in: app_path('Filament/Business/Pages'), for: 'App\\Filament\\Business\\Pages')
            ->pages([
                BusinessDashboard::class,
                ModuleMarketplace::class,
                AiAssistant::class,
                BusinessSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Business/Widgets'), for: 'App\\Filament\\Business\\Widgets')
            ->middleware(PodPanel::tenantMiddleware())
            ->authMiddleware(PodPanel::authMiddleware());
    }
}
