<?php

declare(strict_types=1);

$base = dirname(__DIR__, 2).'/app/Models';

$map = [
    'OrderItem' => ['order' => ['Order', 'order_id'], 'product' => ['Product', 'product_id'], 'variant' => ['ProductVariant', 'variant_id']],
    'CartItem' => ['cart' => ['Cart', 'cart_id'], 'product' => ['Product', 'product_id'], 'variant' => ['ProductVariant', 'variant_id']],
    'Payment' => ['order' => ['Order', 'order_id']],
    'PaymentTransaction' => ['payment' => ['Payment', 'payment_id']],
    'Refund' => ['payment' => ['Payment', 'payment_id'], 'order' => ['Order', 'order_id']],
    'CustomerAddress' => ['customer' => ['Customer', 'customer_id']],
    'StoreDomain' => ['store' => ['Store', 'store_id']],
    'StoreSetting' => ['store' => ['Store', 'store_id']],
    'ProductVariant' => ['product' => ['Product', 'product_id']],
    'ProductImage' => ['product' => ['Product', 'product_id'], 'variant' => ['ProductVariant', 'variant_id']],
    'ProductAttribute' => ['product' => ['Product', 'product_id']],
    'ProductPricing' => ['product' => ['Product', 'product_id'], 'variant' => ['ProductVariant', 'variant_id']],
    'PrintArea' => ['product' => ['Product', 'product_id']],
    'DesignVersion' => ['designAsset' => ['DesignAsset', 'design_asset_id']],
    'Mockup' => ['product' => ['Product', 'product_id'], 'designVersion' => ['DesignVersion', 'design_version_id']],
    'ProductionOrder' => ['order' => ['Order', 'order_id']],
    'ProductionJob' => ['productionOrder' => ['ProductionOrder', 'production_order_id'], 'orderItem' => ['OrderItem', 'order_item_id']],
    'FulfillmentOrder' => ['order' => ['Order', 'order_id']],
    'Shipment' => ['fulfillmentOrder' => ['FulfillmentOrder', 'fulfillment_order_id']],
    'ShipmentItem' => ['shipment' => ['Shipment', 'shipment_id'], 'orderItem' => ['OrderItem', 'order_item_id']],
    'OrderReturn' => ['order' => ['Order', 'order_id']],
    'ReturnItem' => ['return' => ['OrderReturn', 'return_id'], 'orderItem' => ['OrderItem', 'order_item_id']],
    'ProviderOrder' => ['order' => ['Order', 'order_id'], 'printProvider' => ['PrintProvider', 'print_provider_id']],
    'SubscriptionInvoice' => ['subscription' => ['Subscription', 'subscription_id']],
    'TenantDomain' => ['tenant' => ['Tenant', 'tenant_id']],
];

foreach ($map as $class => $relations) {
    $path = "{$base}/{$class}.php";
    if (! is_file($path)) {
        continue;
    }
    $content = file_get_contents($path);
    foreach ($relations as $method => [$related, $foreignKey]) {
        if (str_contains($content, "function {$method}(")) {
            continue;
        }
        $content = str_replace("\n}\n", "\n\n    public function {$method}(): \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo\n    {\n        return \$this->belongsTo({$related}::class, '{$foreignKey}');\n    }\n}\n", $content);
    }
    file_put_contents($path, $content);
}

echo "Foreign relations enhanced\n";
