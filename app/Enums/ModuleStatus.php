<?php

declare(strict_types=1);

namespace App\Enums;

enum ModuleStatus: string
{
    case Inactive = 'inactive';
    case Active = 'active';
    case Suspended = 'suspended';
    case Deprecated = 'deprecated';
}
