<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ModuleDependency extends Model
{
    use HasUlids;

    protected $fillable = [
        'module_key',
        'depends_on_key',
    ];
}
