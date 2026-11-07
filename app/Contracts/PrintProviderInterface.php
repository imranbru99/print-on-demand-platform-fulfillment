<?php

declare(strict_types=1);

namespace App\Contracts;

interface PrintProviderInterface
{
    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function submitOrder(string $providerOrderReference, array $payload): array;

    /**
     * @return array<string, mixed>
     */
    public function getOrderStatus(string $providerOrderReference): array;

    /**
     * @return array<string, mixed>
     */
    public function cancelOrder(string $providerOrderReference): array;
}
