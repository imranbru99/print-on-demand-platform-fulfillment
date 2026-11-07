<?php

declare(strict_types=1);

namespace App\Filament\Platform\Pages;

use App\Models\PlatformSetting;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PlatformSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.platform.pages.platform-settings';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $title = 'Platform Settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = PlatformSetting::query()
            ->pluck('value', 'key')
            ->map(fn ($value) => is_array($value) ? $value : ['value' => $value])
            ->toArray();

        $this->form->fill([
            'settings' => $settings,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Global configuration')
                    ->schema([
                        KeyValue::make('settings')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Add setting')
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = $this->form->getState()['settings'] ?? [];

        foreach ($settings as $key => $value) {
            PlatformSetting::query()->updateOrCreate(
                ['key' => (string) $key],
                ['value' => is_scalar($value) ? ['value' => $value] : $value],
            );
        }

        Notification::make()
            ->title('Platform settings saved')
            ->success()
            ->send();
    }
}
