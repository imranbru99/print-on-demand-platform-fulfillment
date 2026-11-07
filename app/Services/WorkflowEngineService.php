<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AutomationExecution;
use App\Models\AutomationWorkflow;
use Illuminate\Support\Facades\DB;

final class WorkflowEngineService
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function dispatch(AutomationWorkflow $workflow, array $context = []): AutomationExecution
    {
        return DB::transaction(function () use ($workflow, $context): AutomationExecution {
            $execution = AutomationExecution::query()->create([
                'tenant_id' => $workflow->tenant_id,
                'automation_workflow_id' => $workflow->getKey(),
                'status' => 'running',
                'context' => $context,
            ]);

            $definition = $workflow->definition ?? [];
            $result = [
                'steps_executed' => count($definition['steps'] ?? []),
                'status' => 'completed',
            ];

            $execution->update([
                'status' => 'completed',
                'result' => $result,
            ]);

            return $execution->fresh();
        });
    }
}
