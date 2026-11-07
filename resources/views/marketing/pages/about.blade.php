@extends('layouts.marketing')
@section('title', 'About')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'About us', 'subtitle' => 'We build operational software for teams selling customized physical products.'])
    <div class="mx-auto max-w-3xl px-4 py-12 space-y-4 text-ink/80 dark:text-sand/80">
        <p>{{ config('app.name') }} connects storefront experiences with production, payments, and logistics adapters so operators can focus on merchandising and customer relationships.</p>
        <p>Our architecture favors explicit modules, server-validated checkout, and tenant isolation—design choices that reduce surprises as order volume grows.</p>
    </div>
@endsection
