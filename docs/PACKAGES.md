# Package notes

## laravel-admin-panel — intentionally omitted

Do **not** install `imrandevbd/laravel-admin-panel` in this project. It is a competing full admin UI that conflicts with **Filament 3**, which is the canonical admin layer for this SaaS. Running both panel systems leads to duplicated auth flows, conflicting resources, and divergent UX patterns.

Use Filament for all platform, tenant, and operations admin experiences.

## Installed Composer packages (application)

| Package | Version constraint | Role |
|---------|-------------------|------|
| `laravel/framework` | ^12.0 | Core framework |
| `filament/filament` | ^3.3 | Admin UI (Platform, Business, Production) |
| `laravel/sanctum` | ^4.3 | API token support (routes pending) |
| `spatie/laravel-permission` | ^8.5 | RBAC foundation |
| `brick/math` | ^0.14.8 | Decimal-safe money math in pricing services |
| `imrandevbd/laravel-ai-hub` | ^1.7 | Multi-provider AI configuration |
| `imrandevbd/laravel-ai-catalog` | dev-main | Catalog AI extensions |
| `imrandevbd/drutogoti` | dev-main | Project utility package |
| `imrandevbd/laravel-attendance-hub` | dev-main | Attendance/HR integration surface |
| `imrandevbd/laravel-unicode-pdf` | dev-main | Unicode PDF generation |
| `imrandevbd/laravel-universal-slug` | dev-main | Slug generation helpers |

Dev: `laravel/pint`, `laravel/sail`, `laravel/pail`, `phpunit/phpunit`, `mockery/mockery`, `fakerphp/faker`.

## NPM (build)

- `vite`, `laravel-vite-plugin`, `tailwindcss` ^4, `@tailwindcss/vite`, `axios`, `concurrently`

## Configuration published

- `config/ai-hub.php` — AI providers
- `config/pod.php` — modules and `DEMO_MODE`
- `config/permission.php`, `config/sanctum.php`, `config/drutogoti.php`

## Filament upgrade

Composer `post-autoload-dump` runs `php artisan filament:upgrade` — run after deploy when updating Filament.
