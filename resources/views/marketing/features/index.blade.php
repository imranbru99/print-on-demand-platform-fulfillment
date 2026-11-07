@extends('layouts.marketing')

@section('title', 'Features')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-16">
        <h1 class="font-display text-4xl font-bold">Platform modules</h1>
        <p class="mt-4 max-w-2xl text-ink/70 dark:text-sand/70">Composable capabilities you can enable per tenant—from catalog to automation.</p>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($modules as $key => $module)
                <a href="{{ route('marketing.features.show', $key) }}" class="surface-card block hover:border-teal/50">
                    <h2 class="font-display text-lg font-semibold">{{ $module['name'] }}</h2>
                    <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $module['description'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endsection
