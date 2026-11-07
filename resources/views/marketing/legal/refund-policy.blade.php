@extends('layouts.marketing')
@section('title', 'Refund policy')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Refund policy'])
    <div class="mx-auto max-w-3xl px-4 py-12 text-sm text-ink/80 dark:text-sand/80 space-y-4">
        <p>Subscription refunds are handled according to your plan and regional regulations. Consumer-facing store refunds are managed by each merchant.</p>
    </div>
@endsection
