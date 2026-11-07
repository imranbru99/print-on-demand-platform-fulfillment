@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Orders</h1>
    @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->count())
        <ul class="mt-6 space-y-3">
            @foreach ($orders as $order)
                <li class="surface-card flex justify-between text-sm">
                    <span>{{ $order->number }}</span>
                    <a href="{{ route('storefront.account.orders.show', [$store->slug, $order->number]) }}" class="text-teal-dim">View</a>
                </li>
            @endforeach
        </ul>
        {{ $orders->links() }}
    @else
        <p class="mt-4 text-sm text-ink/60">No orders yet.</p>
    @endif
@endsection
