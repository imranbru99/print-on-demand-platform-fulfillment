<?php

declare(strict_types=1);

namespace App\Services\Adapters;

use App\Contracts\PaymentGatewayInterface;

final class SandboxPaymentGateway implements PaymentGatewayInterface
{
    public function authorize(string $idempotencyKey, array $payload): array
    {
        return [
            'idempotency_key' => $idempotencyKey,
            'status' => 'authorized',
            'authorization_reference' => 'sandbox_auth_'.$idempotencyKey,
            'amount' => $payload['amount'] ?? '0.0000',
        ];
    }

    public function capture(string $idempotencyKey, string $authorizationReference, array $payload): array
    {
        return [
            'idempotency_key' => $idempotencyKey,
            'status' => 'captured',
            'payment_reference' => 'sandbox_pay_'.$authorizationReference,
            'amount' => $payload['amount'] ?? '0.0000',
        ];
    }

    public function refund(string $idempotencyKey, string $paymentReference, array $payload): array
    {
        return [
            'idempotency_key' => $idempotencyKey,
            'status' => 'refunded',
            'payment_reference' => $paymentReference,
            'amount' => $payload['amount'] ?? '0.0000',
        ];
    }
}
