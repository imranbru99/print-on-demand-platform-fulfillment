<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\TenantMembership;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantFromUser
{
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            $this->tenantContext->clear();

            return $next($request);
        }

        $tenantId = $request->header('X-Tenant-Id')
            ?? $request->query('tenant_id')
            ?? $user->current_tenant_id;

        if ($tenantId !== null) {
            $membership = TenantMembership::query()
                ->withoutGlobalScopes()
                ->where('user_id', $user->getKey())
                ->where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->with('tenant')
                ->first();

            if ($membership?->tenant !== null) {
                $this->tenantContext->set($membership->tenant);
            } elseif ($user->currentTenant !== null) {
                $this->tenantContext->set($user->currentTenant);
            }
        } elseif ($user->currentTenant !== null) {
            $this->tenantContext->set($user->currentTenant);
        }

        return $next($request);
    }
}
