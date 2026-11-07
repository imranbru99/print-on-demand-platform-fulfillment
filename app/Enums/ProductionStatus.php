<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductionStatus: string
{
    case Queued = 'queued';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
}
