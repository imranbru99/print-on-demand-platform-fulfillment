<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Store;
use App\Services\StorefrontCartService;
use App\Services\StorefrontCheckoutService;
use App\Support\TenantContext;
use Illuminate\Http\JsonResponse;

final class CheckoutController extends Controller
{
    public function __construct(
        private readonly StorefrontCartService $carts,
        private readonly StorefrontCheckoutService $checkout,
        private readonly TenantContext $tenantContext,
    ) {}

    public function store(CheckoutRequest $request, Store $store): JsonResponse
    {
        if ($this->tenantContext->id() === null || $store->tenant_id !== $this->tenantContext->id()) {
            abort(404);
        }

        $token = request()->header('X-Cart-Token');
        $cartToken = is_string($token) && $token !== '' ? $token : null;
        $cart = $this->carts->cartForStore($store, $cartToken)->load('items.product');

        if ($cart->items->isEmpty()) {
            abort(422, 'Cart is empty.');
        }

        $order = $this->checkout->placeOrder(
            $store,
            $cart,
            $request->validated(),
            $request->user(),
        );

        return (new OrderResource($order->load(['items', 'payments'])))
            ->response()
            ->setStatusCode(201);
    }
}
