@extends('layouts.storefront')
@section('title', $category->name.' — '.$store->name)
@section('content')
    <h1 class="font-display text-3xl font-bold">{{ $category->name }}</h1>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($products as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @endforeach
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
@endsection
