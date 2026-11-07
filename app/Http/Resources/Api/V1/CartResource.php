<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cart */
final class CartResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'store_id' => $this->store_id,
            'currency' => $this->currency,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
