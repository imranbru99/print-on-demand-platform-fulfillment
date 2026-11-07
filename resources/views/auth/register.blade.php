@extends('layouts.marketing')
@section('title', 'Register')
@section('content')
    <div class="mx-auto max-w-md px-4 py-16">
        <h1 class="font-display text-3xl font-bold">Create workspace</h1>
        <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">Business registration provisions your tenant via onboarding.</p>
        <form method="POST" action="{{ route('register.submit') }}" class="mt-8 space-y-4">
            @csrf
            <input name="company" value="{{ old('company') }}" placeholder="Company name" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <input name="name" value="{{ old('name') }}" placeholder="Your name" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <input type="password" name="password" placeholder="Password" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <input type="password" name="password_confirmation" placeholder="Confirm password" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <button class="btn-primary w-full">Create account</button>
        </form>
    </div>
@endsection
