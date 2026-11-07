# Roadmap

Priorities inferred from codebase gaps and module definitions. Not a commitment schedule.

## Near term

- [ ] Rich `DatabaseSeeder` — plans, print provider rows, sample tenant/store/products
- [ ] Storefront POST checkout + order creation via `StorefrontCheckoutService`
- [ ] `routes/api.php` + Sanctum-authenticated v1 resources
- [ ] Stripe adapter implementing `PaymentGatewayInterface`
- [ ] Printful adapter implementing `PrintProviderInterface`
- [ ] Queue jobs for webhooks and provider submission
- [ ] Scheduled tasks (subscription renewal, stale cart cleanup)
- [ ] Feature tests for tenancy and checkout

## Medium term

- [ ] Shopify sync integration record + OAuth flow
- [ ] Live shipping (EasyPost or carrier APIs)
- [ ] Encrypted credentials on `provider_accounts`
- [ ] OpenAPI export linked from marketing `/api-docs`
- [ ] Analytics module dashboards beyond Filament widgets
- [ ] Customer-facing design studio persistence wired to `design_assets`

## Long term

- [ ] SSO for enterprise (`/enterprise` marketing promise)
- [ ] Multi-region storage and CDN for mockups
- [ ] Usage-based billing metering on `subscriptions.metadata`
- [ ] Horizontal scale: Redis queues, Octane optional profile

Track delivery in [IMPLEMENTATION_STATUS.md](IMPLEMENTATION_STATUS.md).
