@extends('layouts.account')
@section('account')
    <h1 class="font-display text-2xl font-bold">Returns</h1>
    @forelse ($returns as $return)
        <div class="surface-card mt-4 text-sm">Return — {{ $return->status }}</div>
    @empty
        <p class="mt-4 text-sm text-ink/60">No return requests.</p>
    @endforelse
@endsection
