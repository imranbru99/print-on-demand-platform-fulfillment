@extends('layouts.marketing')
@section('title', 'Privacy')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Privacy policy'])
    <div class="mx-auto max-w-3xl px-4 py-12 text-sm text-ink/80 dark:text-sand/80 space-y-4">
        <p>We process account, order, and support data to operate the platform. Data is isolated per tenant.</p>
        <p>Contact {{ config('mail.from.address', 'privacy@example.com') }} for data access requests.</p>
    </div>
@endsection
