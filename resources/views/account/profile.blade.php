@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Profile</h1>
    <form method="POST" action="{{ route('storefront.account.profile.update', $store->slug) }}" class="mt-6 max-w-md space-y-4">
        @csrf
        <input name="first_name" value="{{ old('first_name', $customer->first_name ?? explode(' ', auth()->user()->name ?? '')[0] ?? '') }}" class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:bg-ink-muted">
        <input name="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}" class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:bg-ink-muted">
        <input name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:bg-ink-muted">
        <button class="btn-primary">Save</button>
    </form>
@endsection
