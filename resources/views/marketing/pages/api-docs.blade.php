@extends('layouts.marketing')
@section('title', 'API')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'API reference', 'subtitle' => 'Sanctum-authenticated APIs with tenant-scoped resources.'])
    <div class="mx-auto max-w-3xl px-4 py-12 font-mono text-sm surface-card">
        <p>POST /api/v1/orders — create order (server pricing required)</p>
        <p class="mt-2">GET /api/v1/products — list catalog for current store context</p>
        <p class="mt-4 text-ink/60 dark:text-sand/60 font-sans">Detailed OpenAPI export can be wired to your integration module.</p>
    </div>
@endsection
