# User roles & access

## User types (`users.type`)

| Type | Filament Platform (`/platform`) | Filament Business (`/app`) | Filament Production (`/production`) |
|------|--------------------------------|-----------------------------|-------------------------------------|
| `platform` | Yes | Yes (needs `current_tenant_id`) | Yes (needs tenant) |
| `business` | No | Yes (needs tenant) | Yes (needs tenant) |
| `production` | No | No | Yes (needs tenant) |

Additional flag: `is_platform_user=true` grants Platform panel access (see `User::canAccessPanel()`).

Suspended users (`suspended_at` not null) cannot access any panel.

## Tenant membership roles

`tenant_memberships.role` is a string (default `member`). Onboarding sets the registering user to **`owner`**.

Spatie Laravel Permission is installed (`HasRoles` on `User`), but **no role seeder** ships with the app—use Filament or `php artisan permission:create-role` when hardening RBAC.

## Storefront customers

End customers are modeled in `customers` and may link to authenticated `users` for account portal routes under `/store/{slug}/account/*`. Registration at `/register` creates a **merchant** user, not a storefront customer record.

## Panel paths

| Panel | URL path | Brand name |
|-------|----------|------------|
| Platform | `/platform` | PrintFlow Platform |
| Business | `/app` | PrintFlow Business |
| Production | `/production` | PrintFlow Production |

All panels use Filament's built-in login unless SSO is added in a future release.

## Middleware

| Alias | Class | Purpose |
|-------|-------|---------|
| `tenant` | `SetTenantFromUser` | Resolve tenant context for admin |
| `module` | `EnsureModuleEnabled` | Abort if module disabled |
| `store.context` | `ResolveStoreFromSlug` | Bind `Store` for storefront |

## Creating a platform operator (manual)

```bash
php artisan tinker
```

```php
$user = \App\Models\User::factory()->create([
    'email' => 'ops@example.com',
    'type' => 'platform',
    'is_platform_user' => true,
]);
```

No demo password is committed—set password via factory or `Hash::make()` in tinker.
