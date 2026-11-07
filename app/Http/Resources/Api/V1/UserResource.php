<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
final class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'ulid' => $this->ulid,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'current_tenant_id' => $this->current_tenant_id,
            'tenant' => $this->whenLoaded('currentTenant', fn () => [
                'id' => $this->currentTenant?->getKey(),
                'name' => $this->currentTenant?->name,
                'slug' => $this->currentTenant?->slug,
            ]),
        ];
    }
}
