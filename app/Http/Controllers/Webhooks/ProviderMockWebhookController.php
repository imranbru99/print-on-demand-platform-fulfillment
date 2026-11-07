<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\ProviderOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class ProviderMockWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if (! $this->verifySignature($request)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $externalReference = (string) $request->input('external_id', $request->input('reference', ''));
        $status = (string) $request->input('status', 'updated');

        if ($externalReference !== '') {
            ProviderOrder::query()
                ->withoutGlobalScopes()
                ->where('external_reference', $externalReference)
                ->orWhere('id', $externalReference)
                ->update(['status' => $status]);
        }

        Log::info('Mock provider webhook processed', [
            'external_reference' => $externalReference,
            'status' => $status,
        ]);

        return response()->json(['received' => true]);
    }

    private function verifySignature(Request $request): bool
    {
        $secret = (string) config('services.print.mock_webhook_secret', 'mock-provider-webhook-secret');
        $signature = (string) $request->header('X-Provider-Signature', $request->header('X-Webhook-Signature', ''));
        $payload = $request->getContent();

        if ($signature === '') {
            return config('app.env') !== 'production';
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }
}
