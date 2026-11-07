<?php

declare(strict_types=1);

$base = dirname(__DIR__);

function plural(string $name): string
{
    return match ($name) {
        'Category' => 'Categories',
        'ModuleRegistry' => 'ModuleRegistries',
        'InventoryItem' => 'InventoryItems',
        'DesignAsset' => 'DesignAssets',
        'ProductionOrder' => 'ProductionOrders',
        'FulfillmentOrder' => 'FulfillmentOrders',
        'SupportTicket' => 'SupportTickets',
        'QualityCheck' => 'QualityChecks',
        'ProductionJob' => 'ProductionJobs',
        'BlogPost' => 'BlogPosts',
        'CmsPage' => 'CmsPages',
        'AuditLog' => 'AuditLogs',
        default => $name.'s',
    };
}

/**
 * @param  array<int, array{model: string, name: string, fields: array<string, string>, group?: string, icon?: string, readonly?: bool, withoutTenantScope?: bool}>  $defs
 */
function scaffold(string $panel, array $defs): void
{
    global $base;

    foreach ($defs as $def) {
        $name = $def['name'];
        $model = $def['model'];
        $fields = $def['fields'];
        $group = $def['group'] ?? 'Management';
        $icon = $def['icon'] ?? 'heroicon-o-rectangle-stack';
        $readonly = $def['readonly'] ?? false;
        $withoutScope = $def['withoutTenantScope'] ?? false;

        $resourceDir = "{$base}/app/Filament/{$panel}/Resources/{$name}Resource";
        $pagesDir = "{$resourceDir}/Pages";
        if (! is_dir($pagesDir)) {
            mkdir($pagesDir, 0777, true);
        }

        $formLines = [];
        $tableLines = [];
        foreach ($fields as $field => $type) {
            $component = match ($type) {
                'textarea' => "Forms\\Components\\Textarea::make('{$field}')",
                'toggle' => "Forms\\Components\\Toggle::make('{$field}')",
                'number' => "Forms\\Components\\TextInput::make('{$field}')->numeric()",
                'datetime' => "Forms\\Components\\DateTimePicker::make('{$field}')",
                'date' => "Forms\\Components\\DatePicker::make('{$field}')",
                default => "Forms\\Components\\TextInput::make('{$field}')",
            };
            $formLines[] = "                {$component},";
            $tableLines[] = "                Tables\\Columns\\TextColumn::make('{$field}'),";
        }

        $formJoined = implode("\n", $formLines);
        $tableJoined = implode("\n", $tableLines);

        $useBuilder = $withoutScope ? "use Illuminate\\Database\\Eloquent\\Builder;\n\n" : "\n";
        $scopeMethod = $withoutScope ? "\n\n    public static function getEloquentQuery(): Builder\n    {\n        return parent::getEloquentQuery()->withoutGlobalScopes();\n    }\n" : '';

        $action = $readonly ? 'Tables\\Actions\\ViewAction::make()' : 'Tables\\Actions\\EditAction::make()';
        $bulk = $readonly ? '[]' : '[Tables\\Actions\\BulkActionGroup::make([Tables\\Actions\\DeleteBulkAction::make()])]';

        $listClass = 'List'.plural($name);
        $pagesBlock = $readonly
            ? "            'index' => Pages\\{$listClass}::route('/'),\n            'view' => Pages\\View{$name}::route('/{record}'),"
            : "            'index' => Pages\\{$listClass}::route('/'),\n            'create' => Pages\\Create{$name}::route('/create'),\n            'edit' => Pages\\Edit{$name}::route('/{record}/edit'),";

        $canCreate = $readonly ? 'false' : 'true';

        $resourcePhp = <<<PHP
<?php

declare(strict_types=1);

namespace App\\Filament\\{$panel}\\Resources;

use App\\Filament\\{$panel}\\Resources\\{$name}Resource\\Pages;
use App\\Models\\{$model};
use Filament\\Forms;
use Filament\\Forms\\Form;
use Filament\\Resources\\Resource;
use Filament\\Tables;
use Filament\\Tables\\Table;
{$useBuilder}class {$name}Resource extends Resource
{
    protected static ?string \$model = {$model}::class;

    protected static ?string \$navigationIcon = '{$icon}';

    protected static ?string \$navigationGroup = '{$group}';

    public static function form(Form \$form): Form
    {
        return \$form->schema([
{$formJoined}
        ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
{$tableJoined}
            ])
            ->filters([])
            ->actions([
                {$action},
            ])
            ->bulkActions({$bulk});
    }

    public static function getPages(): array
    {
        return [
{$pagesBlock}
        ];
    }{$scopeMethod}}
PHP;

        file_put_contents("{$resourceDir}/{$name}Resource.php", $resourcePhp);

        file_put_contents("{$pagesDir}/{$listClass}.php", <<<PHP
<?php

declare(strict_types=1);

namespace App\\Filament\\{$panel}\\Resources\\{$name}Resource\\Pages;

use App\\Filament\\{$panel}\\Resources\\{$name}Resource;
use Filament\\Actions;
use Filament\\Resources\\Pages\\ListRecords;

class {$listClass} extends ListRecords
{
    protected static string \$resource = {$name}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make()->visible({$canCreate}),
        ];
    }
}

PHP);

        if ($readonly) {
            file_put_contents("{$pagesDir}/View{$name}.php", <<<PHP
<?php

declare(strict_types=1);

namespace App\\Filament\\{$panel}\\Resources\\{$name}Resource\\Pages;

use App\\Filament\\{$panel}\\Resources\\{$name}Resource;
use Filament\\Resources\\Pages\\ViewRecord;

class View{$name} extends ViewRecord
{
    protected static string \$resource = {$name}Resource::class;
}

PHP);
        } else {
            file_put_contents("{$pagesDir}/Create{$name}.php", <<<PHP
<?php

declare(strict_types=1);

namespace App\\Filament\\{$panel}\\Resources\\{$name}Resource\\Pages;

use App\\Filament\\{$panel}\\Resources\\{$name}Resource;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{$name} extends CreateRecord
{
    protected static string \$resource = {$name}Resource::class;
}

PHP);
            file_put_contents("{$pagesDir}/Edit{$name}.php", <<<PHP
<?php

declare(strict_types=1);

namespace App\\Filament\\{$panel}\\Resources\\{$name}Resource\\Pages;

use App\\Filament\\{$panel}\\Resources\\{$name}Resource;
use Filament\\Resources\\Pages\\EditRecord;

class Edit{$name} extends EditRecord
{
    protected static string \$resource = {$name}Resource::class;
}

PHP);
        }
    }
}

$platform = [
    ['model' => 'Tenant', 'name' => 'Tenant', 'group' => 'Tenancy', 'icon' => 'heroicon-o-building-office-2', 'fields' => ['name' => 'text', 'slug' => 'text', 'status' => 'text', 'billing_email' => 'text']],
    ['model' => 'Plan', 'name' => 'Plan', 'group' => 'Billing', 'icon' => 'heroicon-o-credit-card', 'fields' => ['key' => 'text', 'name' => 'text', 'price' => 'number', 'currency' => 'text']],
    ['model' => 'Subscription', 'name' => 'Subscription', 'group' => 'Billing', 'icon' => 'heroicon-o-arrow-path', 'withoutTenantScope' => true, 'fields' => ['tenant_id' => 'text', 'plan_id' => 'text', 'status' => 'text']],
    ['model' => 'ModuleRegistry', 'name' => 'ModuleRegistry', 'group' => 'Modules', 'icon' => 'heroicon-o-puzzle-piece', 'fields' => ['key' => 'text', 'name' => 'text', 'version' => 'text']],
    ['model' => 'User', 'name' => 'User', 'group' => 'Access', 'icon' => 'heroicon-o-users', 'fields' => ['name' => 'text', 'email' => 'text', 'type' => 'text', 'is_platform_user' => 'toggle']],
    ['model' => 'AuditLog', 'name' => 'AuditLog', 'group' => 'Compliance', 'icon' => 'heroicon-o-clipboard-document-list', 'readonly' => true, 'withoutTenantScope' => true, 'fields' => ['event' => 'text', 'auditable_type' => 'text', 'created_at' => 'datetime']],
    ['model' => 'CmsPage', 'name' => 'CmsPage', 'group' => 'Content', 'icon' => 'heroicon-o-document-text', 'withoutTenantScope' => true, 'fields' => ['title' => 'text', 'slug' => 'text', 'is_published' => 'toggle']],
    ['model' => 'BlogPost', 'name' => 'BlogPost', 'group' => 'Content', 'icon' => 'heroicon-o-newspaper', 'withoutTenantScope' => true, 'fields' => ['title' => 'text', 'slug' => 'text', 'published_at' => 'datetime']],
    ['model' => 'Faq', 'name' => 'Faq', 'group' => 'Content', 'icon' => 'heroicon-o-question-mark-circle', 'withoutTenantScope' => true, 'fields' => ['question' => 'text', 'answer' => 'textarea', 'sort_order' => 'number']],
];

$business = [
    ['model' => 'Product', 'name' => 'Product', 'group' => 'Catalog', 'icon' => 'heroicon-o-cube', 'fields' => ['name' => 'text', 'slug' => 'text', 'status' => 'text', 'store_id' => 'text', 'category_id' => 'text']],
    ['model' => 'ProductCategory', 'name' => 'Category', 'group' => 'Catalog', 'icon' => 'heroicon-o-tag', 'fields' => ['name' => 'text', 'slug' => 'text']],
    ['model' => 'Store', 'name' => 'Store', 'group' => 'Catalog', 'icon' => 'heroicon-o-building-storefront', 'fields' => ['name' => 'text', 'slug' => 'text', 'status' => 'text']],
    ['model' => 'DesignAsset', 'name' => 'DesignAsset', 'group' => 'Design', 'icon' => 'heroicon-o-photo', 'fields' => ['name' => 'text', 'mime_type' => 'text']],
    ['model' => 'Mockup', 'name' => 'Mockup', 'group' => 'Design', 'icon' => 'heroicon-o-device-phone-mobile', 'fields' => ['name' => 'text', 'status' => 'text']],
    ['model' => 'Customer', 'name' => 'Customer', 'group' => 'Sales', 'icon' => 'heroicon-o-user-group', 'fields' => ['email' => 'text', 'first_name' => 'text', 'last_name' => 'text']],
    ['model' => 'Order', 'name' => 'Order', 'group' => 'Sales', 'icon' => 'heroicon-o-shopping-bag', 'fields' => ['number' => 'text', 'status' => 'text', 'grand_total' => 'number', 'customer_id' => 'text']],
    ['model' => 'ProductionOrder', 'name' => 'ProductionOrder', 'group' => 'Fulfillment', 'icon' => 'heroicon-o-cog-6-tooth', 'fields' => ['status' => 'text', 'priority' => 'text']],
    ['model' => 'FulfillmentOrder', 'name' => 'FulfillmentOrder', 'group' => 'Fulfillment', 'icon' => 'heroicon-o-truck', 'fields' => ['status' => 'text']],
    ['model' => 'Shipment', 'name' => 'Shipment', 'group' => 'Fulfillment', 'icon' => 'heroicon-o-paper-airplane', 'fields' => ['tracking_number' => 'text', 'status' => 'text']],
    ['model' => 'OrderReturn', 'name' => 'Return', 'group' => 'Fulfillment', 'icon' => 'heroicon-o-arrow-uturn-left', 'fields' => ['status' => 'text', 'reason' => 'textarea']],
    ['model' => 'Warehouse', 'name' => 'Warehouse', 'group' => 'Inventory', 'icon' => 'heroicon-o-home-modern', 'fields' => ['name' => 'text', 'code' => 'text']],
    ['model' => 'InventoryItem', 'name' => 'InventoryItem', 'group' => 'Inventory', 'icon' => 'heroicon-o-archive-box', 'fields' => ['sku' => 'text', 'quantity_on_hand' => 'number']],
    ['model' => 'Coupon', 'name' => 'Coupon', 'group' => 'Marketing', 'icon' => 'heroicon-o-ticket', 'fields' => ['code' => 'text', 'discount_type' => 'text', 'value' => 'number']],
    ['model' => 'Campaign', 'name' => 'Campaign', 'group' => 'Marketing', 'icon' => 'heroicon-o-megaphone', 'fields' => ['name' => 'text', 'channel' => 'text', 'status' => 'text']],
    ['model' => 'SupportTicket', 'name' => 'SupportTicket', 'group' => 'Support', 'icon' => 'heroicon-o-lifebuoy', 'fields' => ['subject' => 'text', 'status' => 'text', 'priority' => 'text']],
    ['model' => 'Expense', 'name' => 'Expense', 'group' => 'Finance', 'icon' => 'heroicon-o-banknotes', 'fields' => ['category' => 'text', 'amount' => 'number', 'incurred_on' => 'date']],
    ['model' => 'Invoice', 'name' => 'Invoice', 'group' => 'Finance', 'icon' => 'heroicon-o-document-currency-dollar', 'fields' => ['number' => 'text', 'status' => 'text', 'total' => 'number']],
    ['model' => 'Employee', 'name' => 'Employee', 'group' => 'HR', 'icon' => 'heroicon-o-identification', 'fields' => ['first_name' => 'text', 'last_name' => 'text', 'email' => 'text']],
];

$production = [
    ['model' => 'ProductionJob', 'name' => 'ProductionJob', 'group' => 'Floor', 'icon' => 'heroicon-o-wrench-screwdriver', 'fields' => ['status' => 'text', 'station' => 'text']],
    ['model' => 'QualityCheck', 'name' => 'QualityCheck', 'group' => 'QC', 'icon' => 'heroicon-o-check-badge', 'fields' => ['status' => 'text', 'notes' => 'textarea']],
];

scaffold('Platform', $platform);
scaffold('Business', $business);
scaffold('Production', $production);

echo "Scaffolded Filament resources.\n";
