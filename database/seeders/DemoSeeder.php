<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Enums\SubscriptionStatus;
use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\DesignAsset;
use App\Models\DesignVersion;
use App\Models\Expense;
use App\Models\Faq;
use App\Models\FulfillmentOrder;
use App\Models\Invoice;
use App\Models\Mockup;
use App\Models\ModuleDependency;
use App\Models\ModuleRegistry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PrintArea;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPricing;
use App\Models\ProductVariant;
use App\Models\ProductionJob;
use App\Models\ProductionOrder;
use App\Models\Shipment;
use App\Models\Store;
use App\Models\StoreDomain;
use App\Models\Subscription;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\TenantMembership;
use App\Models\TrackingEvent;
use App\Models\User;
use App\Services\ModuleRegistryService;
use App\Support\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class DemoSeeder extends Seeder
{
    private const DEMO_PASSWORD = 'DemoPass123!';

    /** @var list<string> */
    private const DEMO_COMMON_MODULES = [
        'stores', 'catalog', 'design-studio', 'mockups', 'pricing', 'cart', 'checkout',
        'orders', 'payments', 'customers', 'coupons', 'production', 'quality', 'fulfillment',
        'shipping', 'cms', 'support', 'finance', 'marketing', 'billing', 'integrations',
    ];

    public function run(): void
    {
        $this->seedRolesAndPermissions();
        $this->seedModuleRegistry();
        $plans = $this->seedPlans();
        $tenant = $this->seedDemoTenant($plans['growth']);
        $users = $this->seedDemoUsers($tenant);
        $store = $this->seedStore($tenant);
        $catalog = $this->seedCatalog($tenant, $store, $users);
        $this->seedCustomersAndOrders($tenant, $store, $catalog, $users);
        $this->seedOperationsData($tenant, $catalog);
        $this->seedMarketingContent();
    }

    private function seedRolesAndPermissions(): void
    {
        $permissions = [
            'platform.manage',
            'tenants.manage',
            'modules.manage',
            'catalog.manage',
            'orders.manage',
            'production.manage',
            'finance.manage',
            'support.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $superAdmin->syncPermissions(Permission::all());

        Role::findOrCreate('platform_admin', 'web')
            ->syncPermissions(['platform.manage', 'tenants.manage', 'modules.manage']);

        Role::findOrCreate('business_owner', 'web')
            ->syncPermissions(['catalog.manage', 'orders.manage', 'production.manage', 'finance.manage', 'support.manage']);

        Role::findOrCreate('designer', 'web')
            ->syncPermissions(['catalog.manage']);

        Role::findOrCreate('production_staff', 'web')
            ->syncPermissions(['production.manage', 'orders.manage']);

        Role::findOrCreate('customer', 'web');
    }

    private function seedModuleRegistry(): void
    {
        $modules = config('pod.modules', []);

        foreach ($modules as $key => $meta) {
            ModuleRegistry::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $meta['name'],
                    'description' => $meta['description'] ?? null,
                    'is_core' => in_array($key, config('pod.core_modules', []), true),
                    'default_config' => [],
                ],
            );

            foreach ($meta['dependencies'] ?? [] as $dependsOn) {
                ModuleDependency::query()->firstOrCreate([
                    'module_key' => $key,
                    'depends_on_key' => $dependsOn,
                ]);
            }
        }
    }

    /**
     * @return array<string, Plan>
     */
    private function seedPlans(): array
    {
        $allModuleKeys = array_keys(config('pod.modules', []));

        $definitions = [
            'free-trial' => [
                'name' => 'Free Trial',
                'price' => '0.0000',
                'modules' => ['core', 'tenancy', 'stores', 'catalog', 'cart', 'checkout', 'orders', 'customers'],
            ],
            'starter' => [
                'name' => 'Starter',
                'price' => '29.0000',
                'modules' => ['core', 'tenancy', 'billing', 'stores', 'catalog', 'pricing', 'cart', 'checkout', 'orders', 'payments', 'customers', 'cms'],
            ],
            'growth' => [
                'name' => 'Growth',
                'price' => '79.0000',
                'modules' => ['core', 'tenancy', 'billing', 'stores', 'catalog', 'design-studio', 'mockups', 'pricing', 'cart', 'checkout', 'orders', 'payments', 'customers', 'coupons', 'production', 'fulfillment', 'shipping', 'support', 'cms', 'marketing'],
            ],
            'professional' => [
                'name' => 'Professional',
                'price' => '149.0000',
                'modules' => array_values(array_diff($allModuleKeys, ['automation', 'ai', 'analytics', 'procurement', 'warehouses'])),
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'price' => '399.0000',
                'modules' => $allModuleKeys,
            ],
        ];

        $plans = [];

        foreach ($definitions as $key => $def) {
            $plans[$key] = Plan::query()->updateOrCreate(
                ['key' => $key],
                [
                    'name' => $def['name'],
                    'description' => "{$def['name']} plan for Print-on-Demand businesses.",
                    'billing_interval' => 'monthly',
                    'price' => $def['price'],
                    'currency' => 'USD',
                    'features' => [
                        'modules' => $def['modules'],
                        'stores' => match ($key) {
                            'free-trial', 'starter' => 1,
                            'growth' => 3,
                            'professional' => 10,
                            default => 999,
                        },
                    ],
                    'is_active' => true,
                ],
            );
        }

        return $plans;
    }

    private function seedDemoTenant(Plan $plan): Tenant
    {
        $tenant = Tenant::query()->updateOrCreate(
            ['slug' => 'printflow-demo'],
            [
                'name' => 'PrintFlow Demo Co',
                'status' => 'active',
                'billing_email' => 'owner@pod-demo.test',
                'trial_ends_at' => now()->addDays(30),
            ],
        );

        TenantDomain::query()->updateOrCreate(
            ['domain' => 'printflow-demo.pod.local'],
            [
                'tenant_id' => $tenant->getKey(),
                'is_primary' => true,
                'verified' => true,
            ],
        );

        Subscription::query()->withoutGlobalScopes()->updateOrCreate(
            ['tenant_id' => $tenant->getKey()],
            [
                'plan_id' => $plan->getKey(),
                'status' => SubscriptionStatus::Active->value,
                'starts_at' => now()->subMonth(),
            ],
        );

        /** @var ModuleRegistryService $registry */
        $registry = app(ModuleRegistryService::class);

        $toActivate = array_values(array_unique(array_merge(
            config('pod.core_modules', []),
            self::DEMO_COMMON_MODULES,
        )));

        $activated = [];
        $attempts = 0;
        while (count($activated) < count($toActivate) && $attempts < 50) {
            foreach ($toActivate as $moduleKey) {
                if (isset($activated[$moduleKey])) {
                    continue;
                }

                try {
                    $registry->activate($tenant, $moduleKey);
                    $activated[$moduleKey] = true;
                } catch (\Throwable) {
                    // dependency ordering — retry on next pass
                }
            }
            $attempts++;
        }

        return $tenant;
    }

    /**
     * @return array<string, User>
     */
    private function seedDemoUsers(Tenant $tenant): array
    {
        $password = Hash::make(self::DEMO_PASSWORD);

        $definitions = [
            'platform_admin' => [
                'email' => 'platform-admin@pod-demo.test',
                'name' => 'Platform Admin',
                'type' => 'platform',
                'is_platform_user' => true,
                'membership' => null,
                'roles' => ['super_admin', 'platform_admin'],
            ],
            'owner' => [
                'email' => 'owner@pod-demo.test',
                'name' => 'Demo Owner',
                'type' => 'business',
                'is_platform_user' => false,
                'membership' => 'owner',
                'roles' => ['business_owner'],
            ],
            'designer' => [
                'email' => 'designer@pod-demo.test',
                'name' => 'Demo Designer',
                'type' => 'business',
                'is_platform_user' => false,
                'membership' => 'designer',
                'roles' => ['designer'],
            ],
            'production' => [
                'email' => 'production@pod-demo.test',
                'name' => 'Demo Production',
                'type' => 'production',
                'is_platform_user' => false,
                'membership' => 'production',
                'roles' => ['production_staff'],
            ],
            'customer' => [
                'email' => 'customer@pod-demo.test',
                'name' => 'Demo Customer',
                'type' => 'business',
                'is_platform_user' => false,
                'membership' => 'customer',
                'roles' => ['customer'],
            ],
        ];

        $users = [];

        foreach ($definitions as $key => $def) {
            $user = User::query()->updateOrCreate(
                ['email' => $def['email']],
                [
                    'name' => $def['name'],
                    'password' => $password,
                    'type' => $def['type'],
                    'is_platform_user' => $def['is_platform_user'],
                    'email_verified_at' => now(),
                    'current_tenant_id' => $def['membership'] !== null ? $tenant->getKey() : null,
                ],
            );

            $user->syncRoles($def['roles']);

            if ($def['membership'] !== null) {
                TenantMembership::query()->withoutGlobalScopes()->updateOrCreate(
                    ['tenant_id' => $tenant->getKey(), 'user_id' => $user->getKey()],
                    [
                        'role' => $def['membership'],
                        'status' => 'active',
                        'accepted_at' => now(),
                    ],
                );
            }

            $users[$key] = $user;
        }

        return $users;
    }

    private function seedStore(Tenant $tenant): Store
    {
        $store = Store::query()->withoutGlobalScopes()->updateOrCreate(
            ['tenant_id' => $tenant->getKey(), 'slug' => 'main'],
            [
                'name' => 'PrintFlow Main Store',
                'status' => 'active',
                'currency' => 'USD',
            ],
        );

        StoreDomain::query()->withoutGlobalScopes()->updateOrCreate(
            ['domain' => 'main.printflow-demo.pod.local'],
            [
                'tenant_id' => $tenant->getKey(),
                'store_id' => $store->getKey(),
                'is_primary' => true,
            ],
        );

        return $store;
    }

    /**
     * @return array{categories: list<ProductCategory>, products: list<array{product: Product, variants: list<ProductVariant>}>}
     */
    private function seedCatalog(Tenant $tenant, Store $store, array $users): array
    {
        app(TenantContext::class)->set($tenant);

        $categoryNames = ['Apparel', 'Accessories', 'Home & Living', 'Prints'];
        $categories = [];

        foreach ($categoryNames as $index => $name) {
            $slug = Str::slug($name);
            $categories[] = ProductCategory::query()->withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $tenant->getKey(), 'slug' => $slug],
                ['name' => $name, 'sort_order' => $index + 1],
            );
        }

        $catalog = [
            ['Classic Tee', 'apparel', '19.9900'],
            ['Premium Hoodie', 'apparel', '49.9900'],
            ['Dad Hat', 'accessories', '24.9900'],
            ['Ceramic Mug', 'home-living', '14.9900'],
            ['Canvas Tote', 'accessories', '18.9900'],
            ['Poster Print', 'prints', '12.9900'],
            ['Phone Case', 'accessories', '22.9900'],
            ['Long Sleeve Tee', 'apparel', '27.9900'],
            ['Sticker Sheet', 'prints', '6.9900'],
        ];

        $colorOptions = ['Black', 'White', 'Navy', 'Red'];
        $sizeOptions = ['S', 'M', 'L', 'XL'];

        $products = [];

        foreach ($catalog as $index => [$name, $categorySlug, $basePrice]) {
            $slug = Str::slug($name);
            $category = collect($categories)->first(fn (ProductCategory $c) => Str::slug($c->name) === $categorySlug)
                ?? $categories[$index % count($categories)];

            $product = Product::query()->withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $tenant->getKey(), 'slug' => $slug],
                [
                    'store_id' => $store->getKey(),
                    'category_id' => $category->getKey(),
                    'name' => $name,
                    'status' => ProductStatus::Active->value,
                    'description' => "Demo {$name} for PrintFlow storefront.",
                ],
            );

            PrintArea::query()->withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $tenant->getKey(), 'product_id' => $product->getKey(), 'name' => 'Front'],
                ['width_mm' => 300, 'height_mm' => 350, 'position' => ['x' => 50, 'y' => 80]],
            );

            $variants = [];
            foreach ($colorOptions as $color) {
                foreach ($sizeOptions as $size) {
                    $variantName = "{$color} / {$size}";
                    $variants[] = ProductVariant::query()->withoutGlobalScopes()->updateOrCreate(
                        [
                            'tenant_id' => $tenant->getKey(),
                            'product_id' => $product->getKey(),
                            'name' => $variantName,
                        ],
                        [
                            'sku' => strtoupper(Str::slug($slug.'-'.$color.'-'.$size, '-')),
                            'options' => ['color' => $color, 'size' => $size],
                            'is_default' => $color === 'Black' && $size === 'M',
                        ],
                    );
                }
            }

            foreach ($variants as $variant) {
                ProductPricing::query()->withoutGlobalScopes()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->getKey(),
                        'product_id' => $product->getKey(),
                        'variant_id' => $variant->getKey(),
                    ],
                    [
                        'base_price' => $basePrice,
                        'compare_at_price' => bcmul($basePrice, '1.2000', 4),
                        'currency' => 'USD',
                    ],
                );
            }

            ProductPricing::query()->withoutGlobalScopes()->updateOrCreate(
                [
                    'tenant_id' => $tenant->getKey(),
                    'product_id' => $product->getKey(),
                    'variant_id' => null,
                ],
                ['base_price' => $basePrice, 'currency' => 'USD'],
            );

            $asset = DesignAsset::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'user_id' => $users['designer']->getKey(),
                'name' => "{$name} master artwork",
                'path' => "demo/designs/{$slug}.svg",
                'mime_type' => 'image/svg+xml',
                'bytes' => 4096,
            ]);

            $version = DesignVersion::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'design_asset_id' => $asset->getKey(),
                'version' => 1,
                'layers' => [['type' => 'text', 'content' => 'PrintFlow']],
                'preview_path' => "demo/previews/{$slug}.png",
            ]);

            Mockup::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'product_id' => $product->getKey(),
                'design_version_id' => $version->getKey(),
                'path' => "demo/mockups/{$slug}.png",
                'status' => 'ready',
            ]);

            $products[] = ['product' => $product, 'variants' => $variants];
        }

        return ['categories' => $categories, 'products' => $products];
    }

    private function seedCustomersAndOrders(Tenant $tenant, Store $store, array $catalog, array $users): void
    {
        app(TenantContext::class)->set($tenant);

        $statuses = [
            OrderStatus::PendingPayment->value,
            OrderStatus::Paid->value,
            OrderStatus::InProduction->value,
            OrderStatus::QualityReview->value,
            OrderStatus::ReadyToShip->value,
            OrderStatus::Shipped->value,
            OrderStatus::Delivered->value,
            OrderStatus::Cancelled->value,
            OrderStatus::Refunded->value,
            OrderStatus::Paid->value,
            OrderStatus::InProduction->value,
            OrderStatus::Shipped->value,
        ];

        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = Customer::query()->withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $tenant->getKey(), 'email' => "buyer{$i}@example.com"],
                [
                    'first_name' => "Buyer{$i}",
                    'last_name' => 'Demo',
                    'phone' => '+1555000'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                ],
            );
        }

        Customer::query()->withoutGlobalScopes()->updateOrCreate(
            ['tenant_id' => $tenant->getKey(), 'email' => $users['customer']->email],
            [
                'user_id' => $users['customer']->getKey(),
                'first_name' => 'Demo',
                'last_name' => 'Customer',
            ],
        );

        $orderIndex = 1;
        foreach ($statuses as $status) {
            $bundle = $catalog['products'][$orderIndex % count($catalog['products'])];
            /** @var Product $product */
            $product = $bundle['product'];
            /** @var ProductVariant $variant */
            $variant = $bundle['variants'][0];
            $pricing = ProductPricing::query()->withoutGlobalScopes()
                ->where('variant_id', $variant->getKey())->first();
            $unitPrice = (string) ($pricing?->base_price ?? '19.9900');
            $qty = ($orderIndex % 3) + 1;
            $lineTotal = bcmul($unitPrice, (string) $qty, 4);

            $order = Order::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'store_id' => $store->getKey(),
                'customer_id' => $customers[$orderIndex % count($customers)]->getKey(),
                'number' => 'DEMO-'.str_pad((string) $orderIndex, 5, '0', STR_PAD_LEFT),
                'status' => $status,
                'currency' => 'USD',
                'subtotal' => $lineTotal,
                'tax_total' => '0.0000',
                'shipping_total' => '5.9900',
                'discount_total' => '0.0000',
                'grand_total' => bcadd($lineTotal, '5.9900', 4),
            ]);

            OrderItem::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'order_id' => $order->getKey(),
                'product_id' => $product->getKey(),
                'variant_id' => $variant->getKey(),
                'name' => $product->name,
                'sku' => $variant->sku,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ]);

            if (in_array($status, [OrderStatus::Paid->value, OrderStatus::InProduction->value, OrderStatus::Shipped->value, OrderStatus::Delivered->value, OrderStatus::Refunded->value], true)) {
                Payment::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'order_id' => $order->getKey(),
                    'provider' => 'sandbox',
                    'status' => 'captured',
                    'amount' => $order->grand_total,
                    'currency' => 'USD',
                    'reference' => 'pay_demo_'.$orderIndex,
                ]);
            }

            if (in_array($status, [OrderStatus::InProduction->value, OrderStatus::QualityReview->value, OrderStatus::ReadyToShip->value, OrderStatus::Shipped->value, OrderStatus::Delivered->value], true)) {
                $productionOrder = ProductionOrder::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'order_id' => $order->getKey(),
                    'status' => 'in_progress',
                    'priority' => 100,
                ]);

                ProductionJob::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'production_order_id' => $productionOrder->getKey(),
                    'status' => $status === OrderStatus::InProduction->value ? 'printing' : 'completed',
                    'station' => 'DTG-1',
                ]);
            }

            if (in_array($status, [OrderStatus::Shipped->value, OrderStatus::Delivered->value], true)) {
                $fulfillment = FulfillmentOrder::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'order_id' => $order->getKey(),
                    'status' => 'fulfilled',
                    'idempotency_key' => 'ful_'.$order->getKey(),
                ]);

                $shipment = Shipment::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'fulfillment_order_id' => $fulfillment->getKey(),
                    'carrier' => 'MockCarrier',
                    'tracking_number' => 'TRK'.strtoupper(Str::random(10)),
                    'status' => $status === OrderStatus::Delivered->value ? 'delivered' : 'in_transit',
                    'shipping_cost' => '5.9900',
                ]);

                TrackingEvent::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'shipment_id' => $shipment->getKey(),
                    'status' => 'label_created',
                    'description' => 'Shipping label created',
                    'occurred_at' => now()->subDays(2),
                ]);

                TrackingEvent::query()->withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->getKey(),
                    'shipment_id' => $shipment->getKey(),
                    'status' => 'in_transit',
                    'description' => 'Package in transit',
                    'occurred_at' => now()->subDay(),
                ]);
            }

            $orderIndex++;
        }
    }

    private function seedOperationsData(Tenant $tenant, array $catalog): void
    {
        app(TenantContext::class)->set($tenant);

        Coupon::query()->withoutGlobalScopes()->updateOrCreate(
            ['code' => 'DEMO10'],
            [
                'tenant_id' => $tenant->getKey(),
                'type' => 'percent',
                'value' => '10.0000',
                'max_uses' => 100,
                'is_active' => true,
                'starts_at' => now()->subWeek(),
                'ends_at' => now()->addMonths(3),
            ],
        );

        Expense::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'category' => 'supplies',
            'amount' => '250.0000',
            'currency' => 'USD',
            'incurred_on' => now()->subDays(5)->toDateString(),
        ]);

        $paidOrder = Order::query()->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->where('status', OrderStatus::Paid->value)
            ->first();

        if ($paidOrder !== null) {
            Invoice::query()->withoutGlobalScopes()->create([
                'tenant_id' => $tenant->getKey(),
                'order_id' => $paidOrder->getKey(),
                'number' => 'INV-DEMO-0001',
                'amount' => $paidOrder->grand_total,
                'status' => 'paid',
                'issued_at' => now()->subDays(3),
            ]);
        }

        $customer = Customer::query()->withoutGlobalScopes()->where('tenant_id', $tenant->getKey())->first();
        $order = Order::query()->withoutGlobalScopes()->where('tenant_id', $tenant->getKey())->first();

        $ticket = SupportTicket::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'customer_id' => $customer?->getKey(),
            'order_id' => $order?->getKey(),
            'subject' => 'Where is my order?',
            'status' => 'open',
            'priority' => 'normal',
        ]);

        SupportMessage::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'support_ticket_id' => $ticket->getKey(),
            'body' => 'I placed an order yesterday and would like a tracking update.',
            'is_staff' => false,
        ]);

        SupportMessage::query()->withoutGlobalScopes()->create([
            'tenant_id' => $tenant->getKey(),
            'support_ticket_id' => $ticket->getKey(),
            'body' => 'Thanks for reaching out — your package ships within 24 hours.',
            'is_staff' => true,
        ]);
    }

    private function seedMarketingContent(): void
    {
        CmsPage::query()->withoutGlobalScopes()->updateOrCreate(
            ['slug' => 'about'],
            [
                'tenant_id' => null,
                'title' => 'About PrintFlow',
                'body' => '<p>PrintFlow is a modern print-on-demand platform for growing brands.</p>',
                'is_published' => true,
            ],
        );

        CmsPage::query()->withoutGlobalScopes()->updateOrCreate(
            ['slug' => 'platform-overview'],
            [
                'tenant_id' => null,
                'title' => 'Platform Overview',
                'body' => '<p>Modular SaaS with automated fulfillment and production routing.</p>',
                'is_published' => true,
            ],
        );

        BlogPost::query()->withoutGlobalScopes()->updateOrCreate(
            ['slug' => 'launch-your-pod-brand'],
            [
                'tenant_id' => null,
                'title' => 'Launch your POD brand in a weekend',
                'excerpt' => 'A practical checklist for going live with custom products.',
                'body' => '<p>Start with catalog, mockups, and a single storefront.</p>',
                'published_at' => now()->subDays(7),
            ],
        );

        BlogPost::query()->withoutGlobalScopes()->updateOrCreate(
            ['slug' => 'production-routing-explained'],
            [
                'tenant_id' => null,
                'title' => 'Production routing explained',
                'excerpt' => 'How orders move from paid to shipped.',
                'body' => '<p>Orders progress through production jobs, QC, and fulfillment.</p>',
                'published_at' => now()->subDays(3),
            ],
        );

        $faqs = [
            ['What is print-on-demand?', 'You sell custom products without holding inventory.'],
            ['Can I use my own domain?', 'Yes — connect custom domains per store.'],
            ['Do you support API access?', 'Yes — REST API v1 with token authentication.'],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faq::query()->withoutGlobalScopes()->updateOrCreate(
                ['question' => $question],
                [
                    'tenant_id' => null,
                    'answer' => $answer,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );
        }
    }
}
