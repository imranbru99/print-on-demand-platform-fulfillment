@extends('layouts.marketing')
@section('title', 'Integrations')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Integrations', 'subtitle' => 'Print providers, payments, shipping, and automation hooks.'])
    <div class="mx-auto max-w-6xl px-4 py-12 grid gap-6 md:grid-cols-3">
        @foreach ($modules as $key => $module)
            <div class="surface-card">
                <h2 class="font-display font-semibold">{{ $module['name'] }}</h2>
                <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $module['description'] }}</p>
            </div>
        @endforeach
    </div>
@endsection
