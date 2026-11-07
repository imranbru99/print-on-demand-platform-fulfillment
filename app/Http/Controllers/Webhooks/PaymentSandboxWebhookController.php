<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Enums\OrderStatus;
use App\Events\OrderPaid;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\OrderStateMachine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class PaymentSandboxWebhookController extends Controller
{
    public function __invoke(Request $request, OrderStateMachine $stateMachine): JsonResponse
    {
        if (! $this->verifySignature($request)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $orderId = (string) $request->input('order_id', '');
        $status = (string) $request->input('status', 'captured');

        $order = Order::query()->withoutGlobalScopes()->find($orderId);

        if ($order === null) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        Payment::query()->updateOrCreate(
            [
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->getKey(),
                'provider' => 'sandbox',
            ],
            [
                'status' => $status,
                'amount' => $order->grand_total,
            ],
        );

        if ($order->status === OrderStatus::PendingPayment->value) {
            $order = $stateMachine->transition($order, OrderStatus::Paid);
            event(new OrderPaid($order));
        }

        Log::info('Sandbox payment webhook processed', ['order_id' => $order->getKey()]);

        return response()->json(['received' => true]);
    }

    private function verifySignature(Request $request): bool
    {
        $secret = (string) config('services.payments.sandbox_webhook_secret', 'sandbox-webhook-secret');
        $signature = (string) $request->header('X-Webhook-Signature', '');
        $payload = $request->getContent();

        if ($signature === '') {
            return config('app.env') !== 'production';
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
