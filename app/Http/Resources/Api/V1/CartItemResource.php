<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartItem */
final class CartItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'quantity' => $this->quantity,
            'unit_price' => (string) $this->unit_price,
        ];
    }
}
