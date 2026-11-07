<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Contracts\PrintProviderInterface;
use App\Contracts\ShippingProviderInterface;
use App\Services\Adapters\MockPrintProvider;
use App\Services\Adapters\MockShippingProvider;
use App\Services\Adapters\SandboxPaymentGateway;
use App\Support\TenantContext;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

final class PodServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class);

        $this->app->bind(PrintProviderInterface::class, MockPrintProvider::class);
        $this->app->bind(PaymentGatewayInterface::class, SandboxPaymentGateway::class);
        $this->app->bind(ShippingProviderInterface::class, MockShippingProvider::class);
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('tenant', \App\Http\Middleware\SetTenantFromUser::class);
        $router->aliasMiddleware('module', \App\Http\Middleware\EnsureModuleEnabled::class);
        $router->aliasMiddleware('store.context', \App\Http\Middleware\ResolveStoreFromSlug::class);

        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
