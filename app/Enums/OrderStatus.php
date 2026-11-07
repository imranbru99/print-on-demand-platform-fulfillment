<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Draft = 'draft';
    case PendingPayment = 'pending_payment';
    case Paid = 'paid';
    case InProduction = 'in_production';
    case QualityReview = 'quality_review';
    case ReadyToShip = 'ready_to_ship';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    /**
     * @return list<OrderStatus>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::PendingPayment, self::Cancelled],
            self::PendingPayment => [self::Paid, self::Cancelled],
            self::Paid => [self::InProduction, self::Cancelled, self::Refunded],
            self::InProduction => [self::QualityReview, self::Cancelled],
            self::QualityReview => [self::ReadyToShip, self::InProduction],
            self::ReadyToShip => [self::Shipped],
            self::Shipped => [self::Delivered, self::Refunded],
            self::Delivered => [self::Refunded],
            self::Cancelled, self::Refunded => [],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }
}
