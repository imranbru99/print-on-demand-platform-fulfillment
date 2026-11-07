<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\OrderEvent;
use InvalidArgumentException;

final class OrderStateMachine
{
    public function transition(Order $order, OrderStatus $to, ?int $userId = null, array $payload = []): Order
    {
        $from = OrderStatus::from($order->status);

        if (! $from->canTransitionTo($to)) {
            throw new InvalidArgumentException("Cannot transition order from [{$from->value}] to [{$to->value}].");
        }

        $order->status = $to->value;
        $order->save();

        OrderEvent::query()->create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->getKey(),
            'event' => 'status_changed',
            'payload' => array_merge($payload, [
                'from' => $from->value,
                'to' => $to->value,
            ]),
            'user_id' => $userId,
        ]);

        $fresh = $order->fresh();

        event(new OrderStatusChanged($fresh, $from, $to));

        return $fresh;
    }
}
