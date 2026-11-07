# Testing

## Runner

```bash
php artisan test
# or
composer test
```

## Configuration

`phpunit.xml` sets:

- `APP_ENV=testing`
- `DB_CONNECTION=sqlite` with `DB_DATABASE=:memory:`
- `QUEUE_CONNECTION=sync`
- `CACHE_STORE=array`, `SESSION_DRIVER=array`

`DatabaseSeeder` invokes `DemoSeeder` in the `testing` environment (see `database/seeders/DatabaseSeeder.php`).

GitHub Actions CI is **disabled** for this project. Run tests locally with MySQL or the default in-memory SQLite config.

## Test suites (repository)

| File | Focus |
|------|--------|
| `tests/Unit/ModuleRegistryServiceTest.php` | Module activation dependencies |
| `tests/Unit/PricingEngineServiceTest.php` | Decimal math |
| `tests/Unit/OrderStateMachineTest.php` | Order status transitions |
| `tests/Feature/AuthApiTest.php` | Sanctum login/register |
| `tests/Feature/CheckoutApiTest.php` | API checkout + sandbox payment |
| `tests/Feature/TenantIsolationTest.php` | Tenant scoping / API 404 |
| `tests/Feature/ModuleAccessTest.php` | Disabled module → 403 |
| `tests/Feature/DemoSeederTest.php` | Demo accounts + catalog |
| `tests/Feature/ExampleTest.php` | Homepage smoke |
| `tests/Concerns/SeedsModuleRegistry.php` | Test helper |

## Known failures (investigate locally)

Recent `php artisan test` runs reported failures including:

- `OrderStateMachine` writing `order_events.payload` (array vs JSON cast)
- `CheckoutApiTest` / `DemoSeeder` — table name mismatches (e.g. `product_pricings` vs `product_pricing`) or JSON columns
- `ExampleTest` homepage assertion

Treat a green local `php artisan test` run as the gate before release.

## Static analysis

```bash
./vendor/bin/pint
```

## Related

- [IMPLEMENTATION_STATUS.md](IMPLEMENTATION_STATUS.md)
- [API.md](API.md)
