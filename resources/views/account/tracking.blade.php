@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Tracking</h1>
    @forelse ($shipments as $shipment)
        <div class="surface-card mt-4 text-sm">{{ $shipment->carrier }} — {{ $shipment->tracking_number }}</div>
    @empty
        <p class="mt-4 text-sm text-ink/60">No shipments yet.</p>
    @endforelse
@endsection
