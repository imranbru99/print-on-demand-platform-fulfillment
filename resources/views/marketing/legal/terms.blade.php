@extends('layouts.marketing')
@section('title', 'Terms')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Terms of service'])
    <div class="mx-auto max-w-3xl px-4 py-12 text-sm text-ink/80 dark:text-sand/80 space-y-4">
        <p>By using the service you agree to acceptable use, billing terms, and limitations described in your order form.</p>
        <p>Modules may be enabled or disabled according to your subscription.</p>
    </div>
@endsection
