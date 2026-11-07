<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Tenant;

final class TenantContext
{
    private ?Tenant $tenant = null;

    public function set(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function tenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function id(): ?string
    {
        return $this->tenant?->getKey();
    }

    public function require(): Tenant
    {
        if ($this->tenant === null) {
            throw new \RuntimeException('No tenant has been bound to the current request context.');
        }

        return $this->tenant;
    }

    public function clear(): void
    {
        $this->tenant = null;
    }
}
