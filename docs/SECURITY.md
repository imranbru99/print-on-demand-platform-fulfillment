# Security

## Authentication

- Session-based login for marketing, storefront account, and Filament (`web` guard)
- Passwords hashed via Laravel's `hashed` cast
- Email verification route exists; **stub** verifier at `/email/verify-stub` for demos only — replace with real verification in production
- Sanctum available for future APIs

## Authorization

- **Filament panels** — `User::canAccessPanel()` enforces `type`, `is_platform_user`, and `suspended_at`
- **Tenancy** — `BelongsToTenant` global scope on tenant-owned models; `TenantContext` for requests
- **Modules** — `EnsureModuleEnabled` middleware prevents access when module disabled
- **Spatie Permission** — installed; seed policies/roles before relying on permission checks in custom code

## Data isolation

- Foreign keys enforce tenant/store relationships in migrations
- Provider credentials stored in JSON columns (`provider_accounts.credentials`, `integrations.config`) — **encrypt at rest** before production (Laravel encrypted casts or vault) — not uniformly applied yet

## Integration secrets

- `.env` holds platform keys (`STRIPE_*`, `PRINTFUL_*`, AI keys)
- Never commit `.env`; `.env.example` uses empty placeholders
- API keys table stores **hashes** (`key_hash`, `prefix`), not plaintext secrets

## Transport & headers

- Terminate TLS at load balancer or Nginx in production
- Set `SESSION_SECURE_COOKIE=true` and appropriate `SESSION_DOMAIN` when HTTPS is enabled
- Filament uses Laravel CSRF protection on forms

## File uploads

Design assets and images should validate MIME/size in controllers and Filament forms (audit per resource before go-live).

## Audit

`audit_logs` table and Filament `AuditLogResource` on Platform panel — ensure critical mutations write audit rows as features harden.

## Reporting vulnerabilities

This is an MIT-licensed template project—define a private security contact for your deployment.

## Hardening backlog

See [IMPLEMENTATION_STATUS.md](IMPLEMENTATION_STATUS.md): MFA fields exist on `users` but UI enforcement is not complete; rate limiting on auth routes should be added for production.
