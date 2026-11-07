<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
final class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status instanceof \BackedEnum ? $this->status->value : $this->status,
            'category_id' => $this->category_id,
            'store_id' => $this->store_id,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'pricing' => ProductPricingResource::collection($this->whenLoaded('pricing')),
        ];
    }
}
