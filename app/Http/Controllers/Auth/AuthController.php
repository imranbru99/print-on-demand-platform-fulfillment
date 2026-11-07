<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantOnboardingService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('marketing.home'));
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request, TenantOnboardingService $onboarding): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company' => ['required', 'string', 'max:160'],
            'plan' => ['nullable', 'string', 'max:64'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => 'business',
            'is_platform_user' => false,
        ]);

        $onboarding->onboard($user, $validated['company'], $validated['plan'] ?? null);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('marketing.home')->with('success', 'Welcome aboard. Your workspace is ready.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('marketing.home');
    }

    public function verifyNotice(): View
    {
        return view('auth.verify-email');
    }

    public function verifyStub(Request $request): RedirectResponse
    {
        if ($request->user() !== null && $request->user()->email_verified_at === null) {
            $request->user()->forceFill(['email_verified_at' => now()])->save();
        }

        return redirect()->route('marketing.home')->with('success', 'Email verified (demo stub).');
    }
}
