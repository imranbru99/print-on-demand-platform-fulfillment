<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AiProviderSetting;
use Illuminate\Support\Facades\Http;
use Throwable;

final class AiHubService
{
    public function chat(string $prompt): string
    {
        $setting = AiProviderSetting::query()
            ->where('is_active', true)
            ->first();

        $config = $setting?->config ?? [];
        $apiKey = $config['api_key'] ?? null;
        $endpoint = $config['endpoint'] ?? null;

        if ($setting === null || empty($apiKey) || empty($endpoint)) {
            return $this->fallbackResponse($prompt);
        }

        try {
            $response = Http::timeout(15)
                ->withToken((string) $apiKey)
                ->post(rtrim((string) $endpoint, '/').'/chat', [
                    'prompt' => $prompt,
                ]);

            if ($response->successful()) {
                return (string) ($response->json('reply') ?? $response->body());
            }
        } catch (Throwable) {
            // fall through to safe stub
        }

        return $this->fallbackResponse($prompt);
    }

    private function fallbackResponse(string $prompt): string
    {
        return 'AI Hub is running in offline demo mode. Based on your question about "'
            .str($prompt)->limit(120).'": consider reviewing order backlog, production queue depth, '
            .'and low-stock SKUs in your business dashboard for actionable next steps.';
    }
}
