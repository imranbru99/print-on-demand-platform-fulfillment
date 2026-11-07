# Database

## Engine support

- **Default local:** SQLite (`DB_CONNECTION=sqlite`) via `.env.example`
- **Docker / production:** MySQL 8.x (`pdo_mysql` in Dockerfile)
- **Optional:** PostgreSQL (`pdo_pgsql` extension installed in Docker image; uncomment `postgres` service in compose)

## Identifiers

- Most domain tables use **ULID** primary keys
- `users` remain auto-increment integers with an additional `ulid` column

## Migrations

All custom schema lives in dated migrations under `database/migrations/` (November 2026 batch). Framework tables: users, cache, jobs, permission, sanctum tokens.

### Platform & tenancy

| Table | Purpose |
|-------|---------|
| `tenants` | Merchant organizations |
| `tenant_domains` | Custom domains |
| `tenant_memberships` | User ↔ tenant, role |
| `plans`, `subscriptions`, `subscription_invoices` | SaaS billing |
| `module_registry`, `tenant_modules`, `module_dependencies` | Module catalog |
| `feature_flags`, `platform_settings`, `audit_logs` | Platform ops |

### Commerce

| Table | Purpose |
|-------|---------|
| `stores`, `store_domains`, `store_settings` | Storefront scope |
| `product_categories`, `products`, `product_variants`, `product_images`, `product_attributes`, `print_areas`, `product_pricing` | Catalog |
| `design_assets`, `design_versions`, `mockups` | Design studio |
| `customers`, `customer_addresses` | Buyers |
| `carts`, `cart_items` | Session/guest carts |
| `orders`, `order_items`, `order_addresses`, `order_events`, `order_notes` | Orders |
| `payments`, `payment_transactions`, `refunds` | Payments |
| `coupons`, `promotions` | Discounts |

### Fulfillment chain

| Table | Purpose |
|-------|---------|
| `print_providers`, `provider_accounts`, `provider_products`, `provider_orders` | External print APIs |
| `production_orders`, `production_jobs`, `production_assignments` | Shop floor |
| `quality_checks`, `quality_defects` | QC |
| `fulfillment_orders`, `shipments`, `shipment_items`, `tracking_events` | Packing/shipping |
| `shipping_zones`, `shipping_rates` | Rate tables |
| `returns`, `return_items` | RMA |

### Back office

| Table | Purpose |
|-------|---------|
| `warehouses`, `inventory_items`, `stock_movements`, `stock_reservations` | Inventory |
| `suppliers`, `purchase_orders`, `purchase_order_items` | Procurement |
| `departments`, `employees` | HR |
| `expenses`, `financial_transactions`, `invoices` | Finance |
| `campaigns`, `support_tickets`, `support_messages`, `knowledge_articles` | Marketing/support |
| `cms_pages`, `blog_posts`, `faqs`, `testimonials`, `reviews`, `wishlists` | Content & social |
| `ai_provider_settings` | AI credentials (platform) |
| `integrations`, `integration_logs`, `api_keys`, `webhooks`, `webhook_deliveries` | Integrations |
| `automation_workflows`, `automation_executions` | Automation |

### Laravel infrastructure

| Table | Purpose |
|-------|---------|
| `jobs`, `job_batches`, `failed_jobs` | Queue |
| `cache`, `cache_locks` | Cache |
| `sessions`, `password_reset_tokens` | Auth |
| `personal_access_tokens` | Sanctum |
| Spatie permission tables | Roles/permissions |

## Seeding status

`DatabaseSeeder` calls `DemoSeeder` when `APP_ENV` is `local`, `testing`, or `demo`, or when `config('pod.demo_mode')` is true. Otherwise **no seed data** runs (production-safe default).

Use `php artisan db:seed --class=DemoSeeder` explicitly when needed.

## Model count

`app/Models` contains **88** Eloquent models aligned with the tables above (including pivot-style entities).

## ER diagrams

See [ERD.md](ERD.md) for Mermaid diagrams of core relationships.

## Commands

```bash
php artisan migrate
php artisan migrate:fresh   # destructive
php artisan db:seed         # minimal user only today
```
