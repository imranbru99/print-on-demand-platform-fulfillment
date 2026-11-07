@extends('layouts.storefront')
@section('title', 'Customize — '.$product->name)
@section('content')
    <h1 class="font-display text-2xl font-bold">Customize {{ $product->name }}</h1>
    <livewire:product-customizer
        :store-id="$store->id"
        :product-id="$product->id"
        :variant-id="$product->variants->first()?->id"
        :key="'customizer-'.$product->id"
    />
@endsection
