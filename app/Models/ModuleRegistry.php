<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ModuleRegistry extends Model
{
    use HasUlids;

    protected $table = 'module_registry';

    protected $fillable = [
        'key',
        'name',
        'description',
        'version',
        'is_core',
        'default_config',
    ];

    protected function casts(): array
    {
        return [
            'is_core' => 'boolean',
            'default_config' => 'array',
        ];
    }
}
