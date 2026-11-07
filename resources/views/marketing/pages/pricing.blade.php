@extends('layouts.marketing')

@section('title', 'Pricing')

@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Transparent pricing', 'subtitle' => 'Plans scale with modules and storefront volume. All tiers include sandbox checkout.'])
    <div class="mx-auto max-w-6xl px-4 pb-20">
        <div class="grid gap-6 md:grid-cols-3">
            @forelse ($plans as $plan)
                <div class="surface-card">
                    <h2 class="font-display text-xl font-semibold">{{ $plan->name }}</h2>
                    <p class="mt-4 text-4xl font-bold">{{ $plan->currency ?? 'USD' }} {{ number_format((float) $plan->price, 2) }}</p>
                    <a href="{{ route('register') }}" class="btn-primary mt-6 inline-block">Select</a>
                </div>
            @empty
                <p class="text-ink/70">Seed billing plans to display live pricing, or register to start on the default trial plan.</p>
            @endforelse
        </div>
    </div>
@endsection
