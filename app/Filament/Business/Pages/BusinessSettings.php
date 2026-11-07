<?php

declare(strict_types=1);

namespace App\Filament\Business\Pages;

use App\Models\Store;
use App\Models\StoreSetting;
use App\Support\TenantContext;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class BusinessSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static string $view = 'filament.business.pages.business-settings';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?string $title = 'Business Settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(TenantContext $tenantContext): void
    {
        $tenant = $tenantContext->require();

        $settings = StoreSetting::query()
            ->where('tenant_id', $tenant->getKey())
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill([
            'settings' => $settings,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tenant preferences')
                    ->schema([
                        KeyValue::make('settings')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->addActionLabel('Add setting'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(TenantContext $tenantContext): void
    {
        $tenant = $tenantContext->require();
        $store = Store::query()->where('tenant_id', $tenant->getKey())->first();

        if ($store === null) {
            Notification::make()
                ->title('Create a store before saving settings')
                ->warning()
                ->send();

            return;
        }

        $settings = $this->form->getState()['settings'] ?? [];

        foreach ($settings as $key => $value) {
            StoreSetting::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->getKey(),
                    'store_id' => $store->getKey(),
                    'key' => (string) $key,
                ],
                [
                    'value' => is_array($value) ? $value : ['value' => $value],
                ],
            );
        }

        Notification::make()
            ->title('Business settings saved')
            ->success()
            ->send();
    }
}
