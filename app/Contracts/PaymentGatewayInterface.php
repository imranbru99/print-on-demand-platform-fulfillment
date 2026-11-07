<?php

declare(strict_types=1);

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function authorize(string $idempotencyKey, array $payload): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function capture(string $idempotencyKey, string $authorizationReference, array $payload): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function refund(string $idempotencyKey, string $paymentReference, array $payload): array;
}
