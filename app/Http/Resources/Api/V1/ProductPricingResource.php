<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ProductPricing */
final class ProductPricingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'variant_id' => $this->variant_id,
            'base_price' => (string) $this->base_price,
            'compare_at_price' => $this->compare_at_price !== null ? (string) $this->compare_at_price : null,
            'currency' => $this->currency,
        ];
    }
}
