@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Order {{ $order->number }}</h1>
    <p class="text-sm text-ink/60">Status: {{ $order->status }}</p>
    <ul class="mt-6 space-y-2 text-sm">
        @foreach ($order->items as $item)
            <li>{{ $item->name }} × {{ $item->quantity }}</li>
        @endforeach
    </ul>
@endsection
