<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Store;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveStoreFromSlug
{
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('storeSlug');

        if (! is_string($slug) || $slug === '') {
            abort(404);
        }

        $store = Store::query()
            ->withoutGlobalScopes()
            ->where('slug', $slug)
            ->with('tenant')
            ->firstOrFail();

        $this->tenantContext->set($store->tenant);
        $request->attributes->set('store', $store);

        return $next($request);
    }
}
