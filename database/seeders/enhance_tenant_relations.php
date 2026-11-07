<?php

declare(strict_types=1);

$modelsPath = dirname(__DIR__, 2).'/app/Models';

foreach (glob($modelsPath.'/*.php') as $file) {
    $content = file_get_contents($file);
    if (! str_contains($content, 'BelongsToTenant')) {
        continue;
    }
    if (str_contains($content, 'function tenant(')) {
        continue;
    }

    $insert = <<<'PHP'

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
PHP;

    $content = str_replace("\n}\n", $insert."\n}\n", $content);
    file_put_contents($file, $content);
}

echo "Tenant relations patched\n";
