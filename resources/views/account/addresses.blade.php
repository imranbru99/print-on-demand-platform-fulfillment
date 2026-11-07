@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Addresses</h1>
    @forelse ($addresses as $address)
        <div class="surface-card mt-4 text-sm">{{ $address->line1 ?? 'Address on file' }}</div>
    @empty
        <p class="mt-4 text-sm text-ink/60">No saved addresses.</p>
    @endforelse
@endsection
