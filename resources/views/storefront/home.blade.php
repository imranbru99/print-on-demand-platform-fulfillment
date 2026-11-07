@extends('layouts.storefront')

@section('title', $store->name)

@section('content')
    <section class="rounded-2xl bg-ink px-8 py-14 text-sand">
        <h1 class="font-display text-3xl font-bold md:text-4xl">Welcome to {{ $store->name }}</h1>
        <p class="mt-4 max-w-xl text-sand/80">Custom products made to order. Browse the shop or personalize a design.</p>
        <a href="{{ route('storefront.shop', $store->slug) }}" class="btn-primary mt-8 inline-block">Shop all</a>
    </section>

    @if ($featured->isNotEmpty())
        <h2 class="mt-12 font-display text-2xl font-semibold">Featured</h2>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($featured as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
@endsection
