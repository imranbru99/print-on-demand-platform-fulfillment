<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\ModuleRegistryService;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureModuleEnabled
{
    public function __construct(
        private readonly TenantContext $tenantContext,
        private readonly ModuleRegistryService $moduleRegistry,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $tenant = $this->tenantContext->tenant();

        if ($tenant === null) {
            abort(403, 'Tenant context is required.');
        }

        if (! $this->moduleRegistry->isEnabled($tenant, $moduleKey)) {
            abort(403, "Module [{$moduleKey}] is not enabled for this tenant.");
        }

        return $next($request);
    }
}
