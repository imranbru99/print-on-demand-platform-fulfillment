<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\ModuleStatus;
use App\Http\Controllers\Controller;
use App\Models\ModuleRegistry;
use App\Models\TenantModule;
use App\Support\TenantContext;
use Illuminate\Http\JsonResponse;

final class ModuleController extends Controller
{
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {}

    public function index(): JsonResponse
    {
        $modules = ModuleRegistry::query()->orderBy('name')->get(['key', 'name', 'description', 'is_core']);

        return response()->json(['data' => $modules]);
    }

    public function status(): JsonResponse
    {
        $tenant = $this->tenantContext->require();

        $rows = TenantModule::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->getKey())
            ->get(['module_key', 'status', 'activated_at', 'deactivated_at']);

        $enabled = $rows
            ->filter(fn (TenantModule $row) => $row->status === ModuleStatus::Active->value)
            ->pluck('module_key')
            ->values();

        return response()->json([
            'data' => [
                'tenant_id' => $tenant->getKey(),
                'enabled' => $enabled,
                'modules' => $rows,
            ],
        ]);
    }
}
