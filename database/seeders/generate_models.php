<?php

declare(strict_types=1);

require dirname(__DIR__, 2).'/vendor/autoload.php';

$base = dirname(__DIR__, 2);

$tableMap = [
    'ModuleRegistry' => 'module_registry',
    'ModuleDependency' => 'module_dependencies',
    'OrderReturn' => 'returns',
    'AiProviderSetting' => 'ai_provider_settings',
];

$tenantModels = [
    'TenantDomain' => ['tenant_id', 'domain', 'is_primary', 'verified'],
    'TenantMembership' => ['tenant_id', 'user_id', 'role', 'status'],
    'Subscription' => ['tenant_id', 'plan_id', 'status'],
    'SubscriptionInvoice' => ['tenant_id', 'subscription_id', 'number', 'amount', 'currency', 'status'],
    'TenantModule' => ['tenant_id', 'module_key', 'status', 'config'],
    'FeatureFlag' => ['tenant_id', 'key', 'enabled', 'payload'],
    'AuditLog' => ['tenant_id', 'user_id', 'event', 'auditable_type', 'auditable_id'],
    'Store' => ['tenant_id', 'name', 'slug', 'status', 'currency'],
    'StoreDomain' => ['tenant_id', 'store_id', 'domain', 'is_primary'],
    'StoreSetting' => ['tenant_id', 'store_id', 'key', 'value'],
    'ProductCategory' => ['tenant_id', 'parent_id', 'name', 'slug'],
    'Product' => ['tenant_id', 'store_id', 'category_id', 'name', 'slug', 'status'],
    'ProductVariant' => ['tenant_id', 'product_id', 'name', 'sku', 'options'],
    'ProductImage' => ['tenant_id', 'product_id', 'variant_id', 'path', 'alt'],
    'ProductAttribute' => ['tenant_id', 'product_id', 'key', 'value'],
    'PrintArea' => ['tenant_id', 'product_id', 'name', 'width_mm', 'height_mm'],
    'ProductPricing' => ['tenant_id', 'product_id', 'variant_id', 'base_price', 'currency'],
    'DesignAsset' => ['tenant_id', 'user_id', 'name', 'path'],
    'DesignVersion' => ['tenant_id', 'design_asset_id', 'version', 'layers'],
    'Mockup' => ['tenant_id', 'product_id', 'design_version_id', 'path', 'status'],
    'Customer' => ['tenant_id', 'user_id', 'email', 'first_name', 'last_name'],
    'CustomerAddress' => ['tenant_id', 'customer_id', 'line1', 'city', 'country'],
    'Cart' => ['tenant_id', 'store_id', 'customer_id', 'session_token', 'currency'],
    'CartItem' => ['tenant_id', 'cart_id', 'product_id', 'variant_id', 'quantity', 'unit_price'],
    'Order' => ['tenant_id', 'store_id', 'customer_id', 'number', 'status', 'grand_total'],
    'OrderItem' => ['tenant_id', 'order_id', 'product_id', 'name', 'quantity', 'unit_price', 'line_total'],
    'OrderAddress' => ['tenant_id', 'order_id', 'type', 'line1', 'city', 'country'],
    'OrderEvent' => ['tenant_id', 'order_id', 'event', 'payload'],
    'OrderNote' => ['tenant_id', 'order_id', 'user_id', 'body'],
    'Payment' => ['tenant_id', 'order_id', 'provider', 'status', 'amount'],
    'PaymentTransaction' => ['tenant_id', 'payment_id', 'type', 'status', 'amount', 'idempotency_key'],
    'Refund' => ['tenant_id', 'payment_id', 'order_id', 'amount', 'status'],
    'Coupon' => ['tenant_id', 'code', 'type', 'value'],
    'Promotion' => ['tenant_id', 'name', 'type', 'rules'],
    'ProviderAccount' => ['tenant_id', 'print_provider_id', 'label', 'credentials'],
    'ProviderProduct' => ['tenant_id', 'print_provider_id', 'product_id', 'external_id'],
    'ProviderOrder' => ['tenant_id', 'order_id', 'print_provider_id', 'status'],
    'ProductionOrder' => ['tenant_id', 'order_id', 'status'],
    'ProductionJob' => ['tenant_id', 'production_order_id', 'order_item_id', 'status'],
    'ProductionAssignment' => ['tenant_id', 'production_job_id', 'employee_id', 'user_id'],
    'QualityCheck' => ['tenant_id', 'production_job_id', 'status', 'notes'],
    'QualityDefect' => ['tenant_id', 'quality_check_id', 'code', 'severity'],
    'FulfillmentOrder' => ['tenant_id', 'order_id', 'status', 'idempotency_key'],
    'Shipment' => ['tenant_id', 'fulfillment_order_id', 'carrier', 'tracking_number', 'status'],
    'ShipmentItem' => ['tenant_id', 'shipment_id', 'order_item_id', 'quantity'],
    'TrackingEvent' => ['tenant_id', 'shipment_id', 'status', 'occurred_at'],
    'ShippingZone' => ['tenant_id', 'name', 'countries'],
    'ShippingRate' => ['tenant_id', 'shipping_zone_id', 'name', 'rate'],
    'OrderReturn' => ['tenant_id', 'order_id', 'status', 'reason'],
    'ReturnItem' => ['tenant_id', 'return_id', 'order_item_id', 'quantity'],
    'Warehouse' => ['tenant_id', 'name', 'code'],
    'InventoryItem' => ['tenant_id', 'warehouse_id', 'product_id', 'sku', 'quantity_on_hand'],
    'StockMovement' => ['tenant_id', 'inventory_item_id', 'type', 'quantity'],
    'StockReservation' => ['tenant_id', 'inventory_item_id', 'quantity'],
    'Supplier' => ['tenant_id', 'name', 'email'],
    'PurchaseOrder' => ['tenant_id', 'supplier_id', 'number', 'status', 'total'],
    'PurchaseOrderItem' => ['tenant_id', 'purchase_order_id', 'description', 'quantity', 'unit_cost'],
    'Department' => ['tenant_id', 'name'],
    'Employee' => ['tenant_id', 'user_id', 'department_id', 'employee_number'],
    'Expense' => ['tenant_id', 'category', 'amount', 'currency', 'incurred_on'],
    'FinancialTransaction' => ['tenant_id', 'type', 'amount', 'currency'],
    'Invoice' => ['tenant_id', 'order_id', 'number', 'amount', 'status'],
    'Campaign' => ['tenant_id', 'name', 'channel', 'status'],
    'SupportTicket' => ['tenant_id', 'customer_id', 'order_id', 'subject', 'status'],
    'SupportMessage' => ['tenant_id', 'support_ticket_id', 'user_id', 'body'],
    'Integration' => ['tenant_id', 'type', 'name', 'config'],
    'IntegrationLog' => ['tenant_id', 'integration_id', 'event', 'payload'],
    'ApiKey' => ['tenant_id', 'name', 'key_hash', 'prefix'],
    'Webhook' => ['tenant_id', 'url', 'secret', 'events'],
    'WebhookDelivery' => ['tenant_id', 'webhook_id', 'event'],
    'AutomationWorkflow' => ['tenant_id', 'name', 'trigger', 'definition'],
    'AutomationExecution' => ['tenant_id', 'automation_workflow_id', 'status'],
    'Review' => ['tenant_id', 'product_id', 'customer_id', 'rating', 'body'],
    'Wishlist' => ['tenant_id', 'customer_id', 'product_id', 'variant_id'],
];

$globalModels = [
    'Tenant' => ['name', 'slug', 'status', 'billing_email', 'metadata'],
    'Plan' => ['key', 'name', 'price', 'currency', 'features'],
    'ModuleRegistry' => ['key', 'name', 'description', 'version'],
    'ModuleDependency' => ['module_key', 'depends_on_key'],
    'PlatformSetting' => ['key', 'value'],
    'PrintProvider' => ['key', 'name', 'capabilities'],
    'AiProviderSetting' => ['provider', 'config', 'is_active'],
    'KnowledgeArticle' => ['tenant_id', 'title', 'slug', 'body', 'is_published'],
    'CmsPage' => ['tenant_id', 'title', 'slug', 'body', 'is_published'],
    'BlogPost' => ['tenant_id', 'title', 'slug', 'body', 'published_at'],
    'Faq' => ['tenant_id', 'question', 'answer', 'sort_order'],
    'Testimonial' => ['tenant_id', 'author_name', 'body', 'rating'],
];

$optionalTenantGlobal = ['KnowledgeArticle', 'CmsPage', 'BlogPost', 'Faq', 'Testimonial'];

function pluralTable(string $class, array $tableMap): string
{
    if (isset($tableMap[$class])) {
        return $tableMap[$class];
    }

    return \Illuminate\Support\Str::snake(\Illuminate\Support\Str::pluralStudly($class));
}

function emitModel(string $base, string $class, array $fillable, bool $belongsToTenant, ?string $table = null): void
{
    $table ??= pluralTable($class, [
        'ModuleRegistry' => 'module_registry',
        'ModuleDependency' => 'module_dependencies',
        'OrderReturn' => 'returns',
        'AiProviderSetting' => 'ai_provider_settings',
    ]);

    $defaultTable = \Illuminate\Support\Str::snake(\Illuminate\Support\Str::pluralStudly($class));
    $tableLine = $table !== $defaultTable
        ? "\n    protected \$table = '{$table}';\n"
        : '';

    $usesBelongs = $belongsToTenant ? "use App\Models\Concerns\BelongsToTenant;\n" : '';
    $traitBelongs = $belongsToTenant ? ', BelongsToTenant' : '';
    $boot = $belongsToTenant ? "\n    protected static function booted(): void\n    {\n        static::bootBelongsToTenant();\n    }\n" : '';

    $fillableStr = implode(",\n        ", array_map(static fn (string $f) => "'{$f}'", $fillable));

    $content = <<<PHP
<?php

declare(strict_types=1);

namespace App\Models;

{$usesBelongs}use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class {$class} extends Model
{
    use HasUlids{$traitBelongs};
{$tableLine}
    protected \$fillable = [
        {$fillableStr},
    ];
{$boot}}

PHP;

    $path = "{$base}/app/Models/{$class}.php";
    file_put_contents($path, $content);
}

foreach ($tenantModels as $class => $fillable) {
    emitModel($base, $class, $fillable, true, pluralTable($class, $tableMap));
}

foreach ($globalModels as $class => $fillable) {
    $belongs = in_array($class, $optionalTenantGlobal, true);
    emitModel($base, $class, $fillable, $belongs, pluralTable($class, $tableMap));
}

echo 'Models written: '.(count($tenantModels) + count($globalModels))."\n";
