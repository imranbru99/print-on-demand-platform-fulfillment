@extends('layouts.marketing')

@section('title', $module['name'])

@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16">
        <p class="text-sm uppercase tracking-wider text-teal-dim">Module</p>
        <h1 class="mt-2 font-display text-4xl font-bold">{{ $module['name'] }}</h1>
        <p class="mt-4 text-lg text-ink/80 dark:text-sand/80">{{ $module['description'] }}</p>

        @if ($cms)
            <div class="prose mt-10 dark:prose-invert">{!! nl2br(e($cms->body)) !!}</div>
        @else
            <div class="mt-10 space-y-6 text-sm leading-relaxed text-ink/75 dark:text-sand/75">
                <p>The <strong>{{ $module['name'] }}</strong> module integrates with the rest of the platform through declared dependencies. Enable it for a tenant when you are ready to use its workflows in production.</p>
                @if (! empty($module['dependencies']))
                    <p><span class="font-medium">Depends on:</span> {{ implode(', ', $module['dependencies']) }}</p>
                @endif
                <p><a href="{{ route('register') }}" class="text-teal-dim hover:underline">Start a trial</a> to explore this module in your sandbox tenant.</p>
            </div>
        @endif
    </div>
@endsection
