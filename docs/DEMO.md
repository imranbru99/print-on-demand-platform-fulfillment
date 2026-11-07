# Demo guide

## Demo data seeder

`Database\Seeders\DemoSeeder` provisions a full sample tenant, store, catalog, orders, and operations data.

It runs automatically when:

- `APP_ENV` is `local`, `testing`, or `demo`, **or**
- `DEMO_MODE=true` in `.env` (see `config/pod.php` and `DatabaseSeeder`)

Manual run:

```bash
php artisan db:seed --class=DemoSeeder
# or fresh:
php artisan migrate:fresh --seed
```

## Demo accounts

**Shared password:** `DemoPass123!` (constant `DemoSeeder::DEMO_PASSWORD`)

| Email | Role / purpose |
|-------|----------------|
| `platform-admin@pod-demo.test` | Platform Filament (`/platform`) |
| `owner@pod-demo.test` | Tenant owner — Business panel (`/app`) |
| `designer@pod-demo.test` | Design-focused business user |
| `production@pod-demo.test` | Production panel (`/production`) |
| `customer@pod-demo.test` | Customer-facing test user |

**Tenant:** `PrintFlow Demo Co` — slug `printflow-demo`

**Storefront:** slug `main` → `/store/main/shop`

## Sandbox integrations

Payments, print, and shipping use **mock/sandbox adapters** even in demo data—no live Stripe/Printful calls.

## AI assistant

Configure `AiProviderSetting` or env AI keys for live responses; otherwise Business → AI Assistant uses offline fallback text.

## Email verification

Merchants can use `/email/verify-stub` when logged in (demo stub—not for production).

## Notes

- Demo seeder tests may fail until minor model/JSON casting issues are fixed (see [IMPLEMENTATION_STATUS.md](IMPLEMENTATION_STATUS.md)).
- Do not expose `DEMO_MODE=true` or default demo passwords on public production hosts.
