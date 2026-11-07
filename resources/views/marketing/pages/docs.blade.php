@extends('layouts.marketing')
@section('title', 'Documentation')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Documentation', 'subtitle' => 'Guides for tenants, storefronts, and module enablement.'])
    <div class="mx-auto max-w-3xl px-4 py-12 space-y-6 text-sm">
        <div class="surface-card">
            <h2 class="font-semibold">Getting started</h2>
            <p class="mt-2 text-ink/70 dark:text-sand/70">Register a business account, complete onboarding, create a store, and publish catalog items.</p>
        </div>
        <div class="surface-card">
            <h2 class="font-semibold">Storefront URLs</h2>
            <p class="mt-2 text-ink/70 dark:text-sand/70">Public shops live at <code class="text-teal-dim">/store/{storeSlug}</code> with subdomain mapping supported in the data model.</p>
        </div>
    </div>
@endsection
