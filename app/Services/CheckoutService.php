<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use InvalidArgumentException;

final class CheckoutService
{
    public function __construct(
        private readonly PricingEngineService $pricing,
    ) {}

    /**
     * @return array{subtotal: string, items: list<array{id: string, quantity: int, unit_price: string, line_total: string}>}
     */
    public function validateCartTotals(Cart $cart, string $clientSubtotal): array
    {
        $cart->load('items');

        $lines = $cart->items->map(static fn (CartItem $item): array => [
            'unit_price' => (string) $item->unit_price,
            'quantity' => (int) $item->quantity,
        ]);

        $serverSubtotal = $this->pricing->sumLines($lines);

        if ($this->pricing->compare($serverSubtotal, $clientSubtotal) !== 0) {
            throw new InvalidArgumentException('Cart subtotal does not match server-side pricing.');
        }

        $items = $cart->items->map(fn (CartItem $item): array => [
            'id' => $item->getKey(),
            'quantity' => (int) $item->quantity,
            'unit_price' => (string) $item->unit_price,
            'line_total' => $this->pricing->lineTotal((string) $item->unit_price, (int) $item->quantity),
        ])->values()->all();

        return [
            'subtotal' => $serverSubtotal,
            'items' => $items,
        ];
    }
}
