# Integrations

## Runtime adapters (container bindings)

Configured in `App\Providers\PodServiceProvider`:

| Domain | Interface | Current binding | Live traffic? |
|--------|-----------|-----------------|---------------|
| Payments | `PaymentGatewayInterface` | `SandboxPaymentGateway` | **No** — returns sandbox references |
| Print | `PrintProviderInterface` | `MockPrintProvider` | **No** — mock external IDs |
| Shipping | `ShippingProviderInterface` | `MockShippingProvider` | **No** — stub tracking |

Swap implementations by changing bindings when live adapters exist and credentials are in `.env`.

## Planned / documented providers (not live in code)

Environment placeholders exist in `.env.example`. **No dedicated `StripePrintfulShopify` PHP classes** are in `app/` yet.

| Provider | Purpose | Status |
|----------|---------|--------|
| **Stripe** | Card payments | Adapter interface only; use `SandboxPaymentGateway` today. Set `STRIPE_*` when implementing. |
| **Printful** | POD fulfillment API | Schema: `print_providers`, `provider_accounts`, `provider_orders`. Runtime: **mock**. `PRINTFUL_API_TOKEN` awaits adapter. |
| **Shopify** | External storefront sync | `integrations` records only; **no OAuth/sync jobs**. `SHOPIFY_*` placeholders. |
| **EasyPost** (or similar) | Labels & tracking | `MockShippingProvider` + `EASYPOST_API_KEY` placeholder |

Never describe these as production integrations without credentials and a non-mock binding.

## Data model

- **`integrations`** — per-tenant `type`, `name`, JSON `config`
- **`integration_logs`** — directional event log
- **`print_providers`** — global provider catalog (e.g. future `printful` key)
- **`provider_accounts`** — encrypted credential JSON per tenant
- **`provider_products` / `provider_orders`** — mapping and submission audit

## Webhooks & automation

Tables: `webhooks`, `webhook_deliveries`, `automation_workflows`, `automation_executions`.

`WorkflowEngineService` exists; outbound HTTP delivery and retry queues are **not fully implemented** (no dedicated Jobs).

## AI providers

- Package: `imrandevbd/laravel-ai-hub` — see `config/ai-hub.php` and `OPENAI_API_KEY` et al. in `.env.example`
- App: `AiProviderSetting` model + `AiHubService` with **offline fallback** when inactive or missing keys

## Storage

`FILESYSTEM_DISK=local` by default. Configure `AWS_*` for S3-compatible design asset storage.

## Enabling a live integration (checklist)

1. Implement adapter class satisfying the relevant interface
2. Register in `PodServiceProvider` (or conditional binding based on `config` / env)
3. Store tenant credentials in `provider_accounts` or `integrations.config`
4. Add integration tests with HTTP fakes
5. Document env vars here and in `.env.example`
6. Update [IMPLEMENTATION_STATUS.md](IMPLEMENTATION_STATUS.md)
