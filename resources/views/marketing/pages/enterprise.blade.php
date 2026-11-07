@extends('layouts.marketing')
@section('title', 'Enterprise')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Enterprise', 'subtitle' => 'Dedicated onboarding, SSO-ready identity, and custom integration reviews.'])
    <div class="mx-auto max-w-3xl px-4 py-12 text-ink/80 dark:text-sand/80">
        <p>Talk with us about multi-region fulfillment, advanced RBAC, and bespoke SLA reporting.</p>
        <a href="{{ route('marketing.request-demo') }}" class="btn-primary mt-6 inline-block">Request enterprise demo</a>
    </div>
@endsection
