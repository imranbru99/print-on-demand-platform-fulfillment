# Architecture

## Goals

PrintFlow is a **modular multi-tenant SaaS** for print-on-demand merchants. Tenants subscribe to capabilities (modules), operate stores and production from Filament, and expose a branded storefront per store slug.

Design principles visible in the codebase:

- **Filament-first admin** — no parallel admin package (`laravel-admin-panel` explicitly excluded)
- **Explicit modules** — configuration in `config/pod.php`, runtime checks via `ModuleRegistryService` and `module` middleware
- **Tenant isolation** — `tenant_id` on domain models via `BelongsToTenant` concern; `TenantContext` for HTTP/admin
- **Swappable integrations** — PHP interfaces (`PaymentGatewayInterface`, `PrintProviderInterface`, `ShippingProviderInterface`) bound to mock/sandbox implementations in `PodServiceProvider`

## Request paths

| Surface | Prefix | Middleware / notes |
|---------|--------|-------------------|
| Marketing | `/`, `/pricing`, … | Web stack |
| Auth | `/login`, `/register` | Guest/auth |
| Filament Platform | `/platform` | `User::canAccessPanel('platform')` |
| Filament Business | `/app` | Tenant middleware + business user types |
| Filament Production | `/production` | Tenant middleware + production roles |
| Storefront | `/store/{storeSlug}/…` | `store.context` resolves `Store` |
| REST API | `/api/v1/…` | Sanctum + `tenant` + optional `module:*` |

`bootstrap/app.php` registers **web** and **api** routes. Health check: `/up`.

## Layering

```
HTTP Controllers (Marketing, Storefront, Auth, Api\V1)
        │
        ▼
Application Services (Checkout, Cart, Fulfillment, ModuleRegistry, …)
        │
        ▼
Eloquent Models + State helpers (OrderStateMachine, WorkflowEngineService)
        │
        ▼
MySQL / SQLite (+ optional Redis for queue/cache)
```

**Filament** resources call models directly (standard Filament pattern) with tenant global scopes where applied.

## Multi-tenancy

- **Tenant** — organization record (`tenants`), slug, trial metadata
- **Membership** — `tenant_memberships` links `users` with pivot `role` (e.g. `owner`)
- **Current tenant** — `users.current_tenant_id` set on onboarding
- **Modules** — `tenant_modules` toggles module keys per tenant; marketplace UI in Business panel

On registration, `TenantOnboardingService` creates tenant, membership, optional subscription if a `Plan` exists, and activates `config('pod.core_modules')` (`core`, `tenancy`).

## Module system

Module metadata (name, description, dependencies) lives in config. `EnsureModuleEnabled` middleware aborts 403 when a route requires a module that is off for the current tenant.

Not every Filament resource is wrapped in `module:` middleware yet—entitlements are strongest where middleware is applied; marketplace toggles persist flags in the database.

## Integrations (runtime)

`PodServiceProvider` bindings:

| Interface | Implementation | Live? |
|-----------|----------------|-------|
| `PaymentGatewayInterface` | `SandboxPaymentGateway` | No |
| `PrintProviderInterface` | `MockPrintProvider` | No |
| `ShippingProviderInterface` | `MockShippingProvider` | No |

Database tables (`integrations`, `provider_accounts`, `print_providers`, …) support future live adapters.

## AI

- Package: `imrandevbd/laravel-ai-hub` (`config/ai-hub.php`)
- App wrapper: `App\Services\AiHubService` — uses `AiProviderSetting` or returns offline demo text
- Filament: `AiAssistant` page in Business panel

## Async processing

- Queue default: `database` (`jobs` table migration included)
- No `app/Jobs` classes in the repository yet
- Production Docker/compose runs `queue:work`; scheduler container runs `schedule:work`
- No scheduled tasks defined in `routes/console.php` beyond the default `inspire` command

## Frontend

- Marketing and storefront: Blade templates under `resources/views`
- Asset pipeline: Vite (`resources/css`, `resources/js`)
- Filament ships its own assets

## Related docs

- [DATABASE.md](DATABASE.md) — schema
- [MODULES.md](MODULES.md) — module catalog
- [INTEGRATIONS.md](INTEGRATIONS.md) — external systems
- [DEPLOYMENT.md](DEPLOYMENT.md) — ops layout
