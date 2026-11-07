# API

## Overview

JSON API routes are registered in `bootstrap/app.php` → `routes/api.php`, prefixed by Laravel’s default `/api` prefix.

**Base URL:** `{APP_URL}/api/v1`

Authentication for protected routes: **Laravel Sanctum** (`Authorization: Bearer {token}`) after `POST /api/v1/auth/login` or `register`.

Tenant-scoped routes use the `tenant` middleware (`SetTenantFromUser`). Module-gated groups use `module:{key}`.

Rate limiting: `throttle:api` on the v1 group.

## Public endpoints

| Method | Path | Description |
|--------|------|-------------|
| POST | `/api/v1/auth/login` | Email/password → token |
| POST | `/api/v1/auth/register` | Merchant signup + onboarding |

## Authenticated endpoints

| Method | Path | Module middleware |
|--------|------|-------------------|
| GET | `/api/v1/auth/user` | — |
| GET | `/api/v1/stores` | tenant |
| GET | `/api/v1/stores/{store}` | tenant |
| GET | `/api/v1/categories` | catalog |
| GET | `/api/v1/categories/{category}` | catalog |
| GET | `/api/v1/products` | catalog |
| GET | `/api/v1/products/{product}` | catalog |
| GET | `/api/v1/orders` | orders |
| GET | `/api/v1/orders/{order}` | orders |
| GET | `/api/v1/stores/{store}/cart` | cart |
| POST | `/api/v1/stores/{store}/cart/items` | cart |
| POST | `/api/v1/stores/{store}/checkout` | checkout |
| GET | `/api/v1/coupons` | coupons |
| GET | `/api/v1/modules` | tenant |
| GET | `/api/v1/modules/status` | tenant |

### Checkout API

`POST /api/v1/stores/{store}/checkout` uses `StorefrontCheckoutService` and the **sandbox** payment gateway. Optional header `X-Cart-Token` for guest carts.

## Not yet exposed via API

- Webhook management, API key CRUD, production/fulfillment mutations
- Print provider submission endpoints
- OpenAPI/Scalar export (marketing `/api-docs` remains a stub page)

## Health

`GET /up` — framework health check (not under `/api`).

## Testing

Feature tests: `AuthApiTest`, `CheckoutApiTest`, `TenantIsolationTest`, `ModuleAccessTest` — see [TESTING.md](TESTING.md) for current pass/fail status.
