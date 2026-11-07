<?php

declare(strict_types=1);

namespace App\Filament\Production\Pages;

use App\Filament\Production\Widgets\AssignedJobsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class ProductionDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Production Floor';

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            AssignedJobsWidget::class,
        ];
    }
}
