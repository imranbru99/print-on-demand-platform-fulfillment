# Entity relationship diagrams

Core POD entities (simplified). All major business tables include `tenant_id` unless noted as platform-global.

## Tenancy & billing

```mermaid
erDiagram
    users ||--o{ tenant_memberships : has
    tenants ||--o{ tenant_memberships : has
    users }o--|| tenants : current_tenant
    tenants ||--o{ tenant_domains : has
    tenants ||--o{ subscriptions : has
    plans ||--o{ subscriptions : defines
    subscriptions ||--o{ subscription_invoices : generates
    tenants ||--o{ tenant_modules : enables
    module_registry ||--o{ tenant_modules : catalog
```

## Store & catalog

```mermaid
erDiagram
    tenants ||--o{ stores : owns
    stores ||--o{ store_domains : has
    stores ||--o{ store_settings : has
    tenants ||--o{ product_categories : owns
    product_categories ||--o{ products : contains
    stores ||--o{ products : lists
    products ||--o{ product_variants : has
    products ||--o{ product_images : has
    products ||--o{ print_areas : defines
    product_variants ||--o{ product_pricing : priced_by
```

## Design → cart → order

```mermaid
erDiagram
    customers ||--o{ design_assets : uploads
    design_assets ||--o{ design_versions : versions
    design_versions ||--o{ mockups : previews
    stores ||--o{ carts : session
    carts ||--o{ cart_items : contains
    product_variants ||--o{ cart_items : line
    stores ||--o{ orders : receives
    orders ||--o{ order_items : contains
    orders ||--o{ order_addresses : ships_to
    orders ||--o{ order_events : audit
    orders ||--o{ payments : paid_by
    payments ||--o{ payment_transactions : ledger
    payments ||--o{ refunds : may_refund
```

## Production & fulfillment

```mermaid
erDiagram
    orders ||--o{ provider_orders : routed_to
    print_providers ||--o{ provider_orders : fulfills
    print_providers ||--o{ provider_accounts : credentials
    orders ||--o{ production_orders : schedules
    production_orders ||--o{ production_jobs : breaks_into
    production_jobs ||--o{ production_assignments : assigned
    production_jobs ||--o{ quality_checks : inspected
    quality_checks ||--o{ quality_defects : finds
    orders ||--o{ fulfillment_orders : packs
    fulfillment_orders ||--o{ shipments : ships
    shipments ||--o{ shipment_items : contains
    shipments ||--o{ tracking_events : tracks
    orders ||--o{ returns : may_return
    returns ||--o{ return_items : contains
```

## Integrations

```mermaid
erDiagram
    tenants ||--o{ integrations : configures
    integrations ||--o{ integration_logs : logs
    tenants ||--o{ api_keys : issues
    tenants ||--o{ webhooks : registers
    webhooks ||--o{ webhook_deliveries : delivers
    tenants ||--o{ automation_workflows : defines
    automation_workflows ||--o{ automation_executions : runs
```
