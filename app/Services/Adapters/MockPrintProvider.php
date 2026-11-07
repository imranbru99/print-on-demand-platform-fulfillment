<?php

declare(strict_types=1);

namespace App\Services\Adapters;

use App\Contracts\PrintProviderInterface;

final class MockPrintProvider implements PrintProviderInterface
{
    public function submitOrder(string $providerOrderReference, array $payload): array
    {
        return [
            'reference' => $providerOrderReference,
            'status' => 'submitted',
            'external_id' => 'mock-'.substr($providerOrderReference, 0, 8),
        ];
    }

    public function getOrderStatus(string $providerOrderReference): array
    {
        return [
            'reference' => $providerOrderReference,
            'status' => 'in_production',
        ];
    }

    public function cancelOrder(string $providerOrderReference): array
    {
        return [
            'reference' => $providerOrderReference,
            'status' => 'cancelled',
        ];
    }
}
