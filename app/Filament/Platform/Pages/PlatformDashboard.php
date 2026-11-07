<?php

declare(strict_types=1);

namespace App\Filament\Platform\Pages;

use App\Filament\Platform\Widgets\PlatformStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class PlatformDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Platform Overview';

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            PlatformStatsOverview::class,
        ];
    }
}
