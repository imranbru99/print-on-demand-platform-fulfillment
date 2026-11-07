@extends('layouts.storefront')
@section('title', $product->name)
@section('content')
    <div class="grid gap-10 lg:grid-cols-2">
        <div class="aspect-square rounded-2xl bg-gradient-to-br from-ink to-teal/30"></div>
        <div>
            <h1 class="font-display text-3xl font-bold">{{ $product->name }}</h1>
            @php $price = $product->pricing->first(); @endphp
            @if ($price)
                <p class="mt-4 text-2xl font-semibold text-teal-dim">{{ $price->currency ?? 'USD' }} {{ number_format((float) $price->base_price, 2) }}</p>
            @endif
            <div class="mt-8 flex flex-wrap gap-3">
                <livewire:add-to-cart :store-id="$store->id" :product-id="$product->id" :variant-id="$product->variants->first()?->id" :key="'add-'.$product->id" />
                <a href="{{ route('storefront.product.customize', [$store->slug, $product->slug]) }}" class="btn-ghost">Customize design</a>
            </div>
        </div>
    </div>
@endsection
