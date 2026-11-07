# Modules

PrintFlow defines **34 modules** in `config/pod.php`. Keys are stable identifiers used by `ModuleRegistryService`, the Module Marketplace UI, and optional `module:{key}` route middleware.

**Core modules** auto-enabled on tenant onboarding: `core`, `tenancy`.

## Catalog

| Key | Name | Dependencies |
|-----|------|----------------|
| `core` | Core | — |
| `tenancy` | Tenancy | core |
| `billing` | Billing | tenancy |
| `stores` | Stores | tenancy |
| `catalog` | Product Catalog | stores |
| `design-studio` | Design Studio | catalog |
| `mockups` | Mockups | design-studio |
| `pricing` | Pricing Engine | catalog |
| `cart` | Cart | catalog, pricing |
| `checkout` | Checkout | cart |
| `orders` | Orders | checkout |
| `payments` | Payments | orders |
| `coupons` | Coupons | checkout |
| `promotions` | Promotions | checkout |
| `customers` | Customers | tenancy |
| `print-providers` | Print Providers | orders |
| `production` | Production | orders |
| `quality` | Quality Control | production |
| `fulfillment` | Fulfillment | production |
| `shipping` | Shipping | fulfillment |
| `returns` | Returns | orders |
| `inventory` | Inventory | catalog |
| `warehouses` | Warehouses | inventory |
| `procurement` | Procurement | inventory |
| `hr` | HR | tenancy |
| `finance` | Finance | billing |
| `marketing` | Marketing | customers |
| `support` | Support | customers |
| `cms` | CMS | stores |
| `ai` | AI | core |
| `integrations` | Integrations | core |
| `automation` | Automation | integrations |
| `analytics` | Analytics | orders |
| `reviews` | Reviews & Wishlists | catalog, customers |

## Dependency matrix

Rows depend on columns (✓ = direct dependency).

|  | core | tenancy | billing | stores | catalog | design | mock | price | cart | chk | ord | pay | coup | prom | cust | print | prod | qual | fulf | ship | ret | inv | wh | proc | hr | fin | mkt | sup | cms | ai | integ | auto | anal | rev |
|--|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| core | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| tenancy | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| billing | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| stores | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| catalog | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| design-studio | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| mockups | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| pricing | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| cart | | | | | ✓ | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | |
| checkout | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | |
| orders | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | |
| payments | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | |
| coupons | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | |
| promotions | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | |
| customers | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| print-providers | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | |
| production | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | |
| quality | | | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | |
| fulfillment | | | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | |
| shipping | | | | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | |
| returns | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | |
| inventory | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| warehouses | | | | | | | | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | |
| procurement | | | | | | | | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | | |
| hr | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| finance | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| marketing | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | |
| support | | | | | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | |
| cms | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| ai | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| integrations | ✓ | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
| automation | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | ✓ | | | |
| analytics | | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | | | | | | |
| reviews | | | | | ✓ | | | | | | | | | | ✓ | | | | | | | | | | | | | | | | | | |

(Legend: design = design-studio, mock = mockups, price = pricing, chk = checkout, ord = orders, pay = payments, coup = coupons, prom = promotions, cust = customers, print = print-providers, prod = production, qual = quality, fulf = fulfillment, ship = shipping, ret = returns, inv = inventory, wh = warehouses, proc = procurement, fin = finance, mkt = marketing, sup = support, integ = integrations, auto = automation, anal = analytics, rev = reviews)

## Filament / UX mapping (partial)

| Module area | Primary UI |
|-------------|------------|
| Platform billing/CMS | Filament Platform resources |
| Catalog, orders, ops | Filament Business resources |
| Production, QC | Filament Production resources |
| Module toggles | Business → Module Marketplace |
| Storefront | Blade storefront under `/store/{slug}` |

Enabling a module in the marketplace **does not automatically mount new routes** for every feature; some capabilities are admin-only until storefront wiring is completed.

## Marketing highlights

`config/pod.marketing_features` lists modules featured on the homepage: design-studio, checkout, fulfillment, shipping, integrations, automation, catalog, production.
