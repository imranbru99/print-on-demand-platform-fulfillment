@extends('layouts.storefront')
@section('title', 'Checkout')
@section('content')
    <h1 class="font-display text-3xl font-bold">Checkout</h1>
    <livewire:checkout-form :store-id="$store->id" :client-subtotal="$subtotal" :key="'checkout-'.$store->id" />
@endsection
