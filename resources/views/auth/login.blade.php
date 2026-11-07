@extends('layouts.marketing')
@section('title', 'Log in')
@section('content')
    <div class="mx-auto max-w-md px-4 py-16">
        <h1 class="font-display text-3xl font-bold">Log in</h1>
        <form method="POST" action="{{ route('login.submit') }}" class="mt-8 space-y-4">
            @csrf
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <input type="password" name="password" placeholder="Password" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Remember me</label>
            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            <button class="btn-primary w-full">Sign in</button>
        </form>
        <p class="mt-4 text-sm">No account? <a href="{{ route('register') }}" class="text-teal-dim">Register your business</a></p>
    </div>
@endsection
