@extends('layouts.storefront')
@section('title', 'Shop — '.$store->name)
@section('content')
    <h1 class="font-display text-3xl font-bold">Shop</h1>
    @if ($categories->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2 text-sm">
            @foreach ($categories as $cat)
                <a href="{{ route('storefront.shop.category', [$store->slug, $cat->slug]) }}" class="rounded-full border border-ink/15 px-3 py-1 hover:border-teal">{{ $cat->name }}</a>
            @endforeach
        </div>
    @endif
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($products as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @empty
            <p class="text-ink/60">No products published yet.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
@endsection
