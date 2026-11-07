<?php

declare(strict_types=1);

require dirname(__DIR__, 2).'/vendor/autoload.php';

$base = dirname(__DIR__, 2).'/app/Models';

$enhancements = [
    'Tenant' => <<<'PHP'

    public function domains(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function memberships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TenantMembership::class);
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function modules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function stores(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Store::class);
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'trial_ends_at' => 'datetime',
        ];
    }
PHP,
    'Subscription' => <<<'PHP'

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubscriptionInvoice::class);
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }
PHP,
    'Plan' => <<<'PHP'

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }
PHP,
    'Order' => <<<'PHP'

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderEvent::class);
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'subtotal' => 'decimal:4',
            'tax_total' => 'decimal:4',
            'shipping_total' => 'decimal:4',
            'discount_total' => 'decimal:4',
            'grand_total' => 'decimal:4',
        ];
    }
PHP,
    'Product' => <<<'PHP'

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function pricing(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPricing::class);
    }

    public function printAreas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PrintArea::class);
    }

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\ProductStatus::class,
        ];
    }
PHP,
    'Cart' => <<<'PHP'

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
PHP,
    'TenantModule' => <<<'PHP'

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'activated_at' => 'datetime',
            'deactivated_at' => 'datetime',
            'status' => \App\Enums\ModuleStatus::class,
        ];
    }
PHP,
    'TenantMembership' => <<<'PHP'

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
PHP,
];

foreach ($enhancements as $class => $snippet) {
    $path = "{$base}/{$class}.php";
    $content = file_get_contents($path);
    $content = str_replace("\n}\n", $snippet."\n}\n", $content);
    file_put_contents($path, $content);
}

echo "Enhanced ".count($enhancements)." models\n";
