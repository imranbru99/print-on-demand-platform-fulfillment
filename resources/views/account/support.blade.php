@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Support</h1>
    @forelse ($tickets as $ticket)
        <div class="surface-card mt-4 text-sm">{{ $ticket->subject }} — {{ $ticket->status }}</div>
    @empty
        <p class="mt-4 text-sm text-ink/60">Open a ticket from the merchant admin when the support module is enabled.</p>
    @endforelse
@endsection
