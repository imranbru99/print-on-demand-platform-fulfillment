<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\TenantOnboardingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->validated('email'))->first();

        if ($user === null || ! Hash::check($request->validated('password'), $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 422);
        }

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user->load('currentTenant')),
            ],
        ]);
    }

    public function register(RegisterRequest $request, TenantOnboardingService $onboarding): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => 'business',
            'is_platform_user' => false,
        ]);

        $onboarding->onboard($user, $validated['company'], $validated['plan'] ?? null);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user->fresh()->load('currentTenant')),
            ],
        ], 201);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()->load('currentTenant')),
        ]);
    }
}
