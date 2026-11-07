# Implementation status

Last reviewed against the repository: migrations, `app/`, `routes/`, `tests/`, and integration bindings.

## Summary

| Area | State |
|------|--------|
| Schema / models | **Strong** — broad migrations + 88 models |
| Filament admin | **Strong** — 3 panels, Business/Platform resources + automation workflows CRUD |
| Marketing site | **Implemented** — Blade pages |
| Storefront | **Implemented** — browse/cart/checkout Blade + Livewire checkout completes orders |
| REST API | **Partial** — `/api/v1` auth, catalog, cart, checkout, orders read |
| Queue jobs | **Implemented** — paid-order pipeline, provider submit, tracking sync, documents, webhooks, automation |
| Scheduler tasks | **Implemented** — tracking (15m), webhook retries (hourly), cart cleanup (daily) |
| Tests | **Baseline** — 17 PHPUnit feature/unit tests (see below) |
| Live integrations | **Mock/sandbox** — see Integrations |
| `laravel-admin-panel` | **Not installed** (Filament is canonical admin) |

## Completed

- Multi-tenant schema (tenants, memberships, modules)
- Module registry config (34 modules) + marketplace toggle UI
- Tenant onboarding on `/register`
- Filament Platform / Business / Production panels
- Storefront route tree with `store.context` middleware
- Service layer: cart, checkout, pricing, order state machine, fulfillment, production routing, workflow engine
- Contract-based payment/print/shipping with mock adapters
- Queue jobs under `app/Jobs/` wired to `OrderPaid` / scheduler
- Inbound webhooks: `POST /webhooks/payments/sandbox`, `POST /webhooks/providers/mock` (signature stubs)
- Sanctum + permission tables migrated
- Vite/Tailwind asset pipeline
- Docker scaffolding (GitHub Actions CI intentionally disabled)
- Documentation set (`docs/*`, README)
- `DemoSeeder` + conditional `DatabaseSeeder` when local/testing/demo/`DEMO_MODE`
- API v1 controllers under `App\Http\Controllers\Api\V1`
- ProductResource AI draft action via `AiCatalog` facade (draft status only)

## In progress / partial

- Subscription/plan enforcement at runtime (tables exist; limited seed data)
- Module middleware applied selectively (not all routes gated)
- AI assistant (works with fallback; full ai-hub wiring per tenant optional)
- CMS/blog on marketing site (reads DB when published; may be empty)
- Spatie roles (package integrated; seeding/policies thin)
- Outbound webhook delivery (job present; demo mode skips HTTP)

## Pending

- API key authentication HTTP flow (table exists)
- Expanded API surface (fulfillment writes)
- Printful / Stripe / Shopify **live** adapter classes
- Email verification production flow (replace stub)
- Comprehensive PHPUnit/Pest coverage beyond baseline
- OpenAPI / Scribe documentation export

## Known issues / limitations

1. `DatabaseSeeder` only calls `DemoSeeder` in local/testing/demo/`DEMO_MODE`—production seed is empty by default
2. `composer.json` requires PHP ^8.2; Docker uses 8.3 — align in production
3. Demo mode suppresses outbound webhook HTTP and notification stubs for safer local demos
4. Filament resource count < full module surface (some modules are schema-only)

## Integrations awaiting credentials

| Integration | Code binding | Env vars | Notes |
|-------------|--------------|----------|-------|
| Stripe | `SandboxPaymentGateway` only | `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` | Not live |
| Printful | `MockPrintProvider` | `PRINTFUL_API_TOKEN`, `PRINTFUL_SANDBOX` | Schema ready |
| Shopify | None | `SHOPIFY_*` | Config placeholder only |
| Shipping API | `MockShippingProvider` | `EASYPOST_API_KEY`, `SHIPPING_SANDBOX` | Not live |
| OpenAI / AI Hub | Package + fallback service | `OPENAI_API_KEY`, `AI_HUB_*` | Optional |

## Tests

Run `php artisan test` for current status.

| Suite | Count | Notes |
|-------|-------|-------|
| **Total** | **17 passed** (as of last full run) | SQLite in-memory, sync queue |
| Feature | AuthApi, CheckoutApi, DemoSeeder, ModuleAccess, TenantIsolation, Example, **OrderPaidProduction** | Checkout + order paid → production path |
| Unit | ModuleRegistry, OrderStateMachine, PricingEngine, Example | State machine + pricing |

**Demo seeder:** works when `APP_ENV=local|testing|demo` or `DEMO_MODE=true`.

## Remaining acceptance criteria (suggested)

- [x] Merchant can complete storefront checkout creating `orders` + `payments` rows
- [x] Paid orders enqueue production routing via `OrderPaid` / `ProcessPaidOrderJob`
- [ ] Merchant can submit `provider_orders` through a real or sandbox Printful adapter
- [x] Platform can seed plans and modules for repeatable demos (`DemoSeeder` + `pod:demo-reset`)
- [ ] API client can list products with tenant API key
- [ ] Local test suite passes with MySQL migrations and asset build (CI disabled)
- [ ] Production deploy docs followed with queue + scheduler running

Update this file when adapters, APIs, or seeders land.
