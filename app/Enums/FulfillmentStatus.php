<?php

declare(strict_types=1);

namespace App\Enums;

enum FulfillmentStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Packed = 'packed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
}
