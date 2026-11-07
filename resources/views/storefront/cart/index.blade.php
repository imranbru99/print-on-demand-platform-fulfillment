@extends('layouts.storefront')
@section('title', 'Cart')
@section('content')
    <h1 class="font-display text-3xl font-bold">Your cart</h1>
    @if ($cart->items->isEmpty())
        <p class="mt-6 text-ink/60">Your cart is empty.</p>
    @else
        <ul class="mt-6 divide-y divide-ink/10 dark:divide-sand/10">
            @foreach ($cart->items as $item)
                <li class="flex justify-between py-4 text-sm">
                    <span>{{ $item->product?->name }} × {{ $item->quantity }}</span>
                    <span>{{ number_format((float) $item->unit_price * $item->quantity, 2) }}</span>
                </li>
            @endforeach
        </ul>
        <p class="mt-6 text-lg font-semibold">Subtotal (server): {{ number_format((float) $subtotal, 2) }}</p>
        <a href="{{ route('storefront.checkout', $store->slug) }}" class="btn-primary mt-6 inline-block">Checkout</a>
    @endif
@endsection
