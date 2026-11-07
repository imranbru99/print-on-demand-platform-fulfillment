@extends('layouts.marketing')
@section('title', 'Request demo')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Request a demo', 'subtitle' => 'See catalog, checkout validation, and fulfillment routing live.'])
    <form method="POST" action="{{ route('marketing.request-demo.submit') }}" class="mx-auto max-w-lg px-4 py-12 space-y-4">
        @csrf
        <input name="company" placeholder="Company" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        <input name="name" placeholder="Your name" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        <input type="email" name="email" placeholder="Email" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        <input name="volume" placeholder="Monthly order volume (optional)" class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        <button type="submit" class="btn-primary">Submit request</button>
    </form>
@endsection
