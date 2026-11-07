<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\AutomationWorkflow;
use App\Services\WorkflowEngineService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessAutomationWorkflowJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $context
     */
    public function __construct(
        public readonly string $workflowId,
        public readonly array $context = [],
    ) {}

    public function handle(WorkflowEngineService $workflowEngine): void
    {
        $workflow = AutomationWorkflow::query()
            ->withoutGlobalScopes()
            ->where('is_active', true)
            ->find($this->workflowId);

        if ($workflow === null) {
            return;
        }

        $workflowEngine->dispatch($workflow, $this->context);
    }
}
