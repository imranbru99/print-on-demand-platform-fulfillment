<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
final class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'number' => $this->number,
            'status' => $this->status,
            'currency' => $this->currency,
            'subtotal' => (string) $this->subtotal,
            'grand_total' => (string) $this->grand_total,
            'store_id' => $this->store_id,
            'customer_id' => $this->customer_id,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
