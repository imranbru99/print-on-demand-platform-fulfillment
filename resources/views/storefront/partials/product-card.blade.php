@php
    $price = $product->pricing->first();
@endphp
<a href="{{ route('storefront.product.show', [$store->slug, $product->slug]) }}" class="surface-card block transition hover:-translate-y-1">
    <div class="aspect-square rounded-xl bg-gradient-to-br from-sand/40 to-teal/20"></div>
    <h3 class="mt-3 font-medium">{{ $product->name }}</h3>
    @if ($price)
        <p class="text-sm text-teal-dim">{{ $price->currency ?? 'USD' }} {{ number_format((float) $price->base_price, 2) }}</p>
    @endif
</a>
