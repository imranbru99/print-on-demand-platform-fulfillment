@extends('layouts.storefront')
@section('title', 'Thank you')
@section('content')
    <div class="surface-card text-center">
        <h1 class="font-display text-3xl font-bold">Thank you</h1>
        <p class="mt-4">Order <strong>{{ $orderNumber }}</strong> was placed with sandbox payment.</p>
        <a href="{{ route('storefront.account.orders', $store->slug) }}" class="btn-primary mt-6 inline-block">View orders</a>
    </div>
@endsection
