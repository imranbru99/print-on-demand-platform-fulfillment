@extends('layouts.marketing')
@section('title', 'Verify email')
@section('content')
    <div class="mx-auto max-w-md px-4 py-16 text-center">
        <h1 class="font-display text-2xl font-bold">Verify your email</h1>
        <p class="mt-4 text-sm text-ink/70">Check your inbox for a verification link. For local demos you can use the stub route.</p>
        <a href="{{ route('verification.stub') }}" class="btn-primary mt-6 inline-block">Verify (demo stub)</a>
    </div>
@endsection
