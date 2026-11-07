<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\Store;
use App\Services\StorefrontCartService;
use App\Support\TenantContext;
use Illuminate\Http\JsonResponse;

final class CartController extends Controller
{
    public function __construct(
        private readonly StorefrontCartService $carts,
        private readonly TenantContext $tenantContext,
    ) {}

    public function show(Store $store): CartResource
    {
        $this->assertStoreTenant($store);
        $cart = $this->carts->cartForStore($store, $this->cartToken())->load('items');

        return (new CartResource($cart))->additional([
            'meta' => ['cart_token' => $cart->session_token],
        ]);
    }

    public function addItem(AddCartItemRequest $request, Store $store): CartResource
    {
        $this->assertStoreTenant($store);

        $cart = $this->carts->addItem(
            $store,
            $request->validated('product_id'),
            $request->validated('variant_id'),
            (int) $request->validated('quantity'),
            $this->cartToken(),
        )->load('items');

        return (new CartResource($cart))->additional([
            'meta' => ['cart_token' => $cart->session_token],
        ]);
    }

    private function cartToken(): ?string
    {
        $token = request()->header('X-Cart-Token');

        return is_string($token) && $token !== '' ? $token : null;
    }

    private function assertStoreTenant(Store $store): void
    {
        $tenantId = $this->tenantContext->id();

        if ($tenantId === null || $store->tenant_id !== $tenantId) {
            abort(404);
        }
    }
}
