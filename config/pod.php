<?php

declare(strict_types=1);

return [
    'demo_mode' => (bool) env('DEMO_MODE', false),

    'marketing_features' => [
        'design-studio',
        'checkout',
        'fulfillment',
        'shipping',
        'integrations',
        'automation',
        'catalog',
        'production',
    ],

    'core_modules' => [
        'core',
        'tenancy',
    ],

    'modules' => [
        'core' => [
            'name' => 'Core',
            'description' => 'Platform kernel, settings, and audit logging.',
            'dependencies' => [],
        ],
        'tenancy' => [
            'name' => 'Tenancy',
            'description' => 'Tenant isolation, domains, and memberships.',
            'dependencies' => ['core'],
        ],
        'billing' => [
            'name' => 'Billing',
            'description' => 'Plans, subscriptions, and invoices.',
            'dependencies' => ['tenancy'],
        ],
        'stores' => [
            'name' => 'Stores',
            'description' => 'Multi-store storefront management.',
            'dependencies' => ['tenancy'],
        ],
        'catalog' => [
            'name' => 'Product Catalog',
            'description' => 'Categories, products, variants, and attributes.',
            'dependencies' => ['stores'],
        ],
        'design-studio' => [
            'name' => 'Design Studio',
            'description' => 'Customer design assets and versions.',
            'dependencies' => ['catalog'],
        ],
        'mockups' => [
            'name' => 'Mockups',
            'description' => 'Product mockup generation.',
            'dependencies' => ['design-studio'],
        ],
        'pricing' => [
            'name' => 'Pricing Engine',
            'description' => 'Variant pricing and decimal-safe calculations.',
            'dependencies' => ['catalog'],
        ],
        'cart' => [
            'name' => 'Cart',
            'description' => 'Shopping carts and line items.',
            'dependencies' => ['catalog', 'pricing'],
        ],
        'checkout' => [
            'name' => 'Checkout',
            'description' => 'Server-validated checkout flow.',
            'dependencies' => ['cart'],
        ],
        'orders' => [
            'name' => 'Orders',
            'description' => 'Order management and state machine.',
            'dependencies' => ['checkout'],
        ],
        'payments' => [
            'name' => 'Payments',
            'description' => 'Payment capture, transactions, and refunds.',
            'dependencies' => ['orders'],
        ],
        'coupons' => [
            'name' => 'Coupons',
            'description' => 'Discount codes.',
            'dependencies' => ['checkout'],
        ],
        'promotions' => [
            'name' => 'Promotions',
            'description' => 'Rule-based promotions.',
            'dependencies' => ['checkout'],
        ],
        'customers' => [
            'name' => 'Customers',
            'description' => 'Customer profiles and addresses.',
            'dependencies' => ['tenancy'],
        ],
        'print-providers' => [
            'name' => 'Print Providers',
            'description' => 'External print provider integrations.',
            'dependencies' => ['orders'],
        ],
        'production' => [
            'name' => 'Production',
            'description' => 'Production orders, jobs, and assignments.',
            'dependencies' => ['orders'],
        ],
        'quality' => [
            'name' => 'Quality Control',
            'description' => 'QC checks and defect tracking.',
            'dependencies' => ['production'],
        ],
        'fulfillment' => [
            'name' => 'Fulfillment',
            'description' => 'Fulfillment orders and packing.',
            'dependencies' => ['production'],
        ],
        'shipping' => [
            'name' => 'Shipping',
            'description' => 'Zones, rates, shipments, and tracking.',
            'dependencies' => ['fulfillment'],
        ],
        'returns' => [
            'name' => 'Returns',
            'description' => 'Return requests and item processing.',
            'dependencies' => ['orders'],
        ],
        'inventory' => [
            'name' => 'Inventory',
            'description' => 'Stock levels, movements, and reservations.',
            'dependencies' => ['catalog'],
        ],
        'warehouses' => [
            'name' => 'Warehouses',
            'description' => 'Warehouse locations.',
            'dependencies' => ['inventory'],
        ],
        'procurement' => [
            'name' => 'Procurement',
            'description' => 'Suppliers and purchase orders.',
            'dependencies' => ['inventory'],
        ],
        'hr' => [
            'name' => 'HR',
            'description' => 'Employees and departments.',
            'dependencies' => ['tenancy'],
        ],
        'finance' => [
            'name' => 'Finance',
            'description' => 'Expenses, transactions, and invoicing.',
            'dependencies' => ['billing'],
        ],
        'marketing' => [
            'name' => 'Marketing',
            'description' => 'Campaigns and outreach.',
            'dependencies' => ['customers'],
        ],
        'support' => [
            'name' => 'Support',
            'description' => 'Tickets, messages, and knowledge base.',
            'dependencies' => ['customers'],
        ],
        'cms' => [
            'name' => 'CMS',
            'description' => 'Pages, blog, FAQs, and testimonials.',
            'dependencies' => ['stores'],
        ],
        'ai' => [
            'name' => 'AI',
            'description' => 'AI provider settings and catalog integrations.',
            'dependencies' => ['core'],
        ],
        'integrations' => [
            'name' => 'Integrations',
            'description' => 'Third-party integrations, API keys, and webhooks.',
            'dependencies' => ['core'],
        ],
        'automation' => [
            'name' => 'Automation',
            'description' => 'Workflows and execution engine.',
            'dependencies' => ['integrations'],
        ],
        'analytics' => [
            'name' => 'Analytics',
            'description' => 'Operational analytics and reporting hooks.',
            'dependencies' => ['orders'],
        ],
        'reviews' => [
            'name' => 'Reviews & Wishlists',
            'description' => 'Product reviews and customer wishlists.',
            'dependencies' => ['catalog', 'customers'],
        ],
    ],
];
