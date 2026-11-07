@extends('layouts.marketing')
@section('title', 'Help')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Help center', 'subtitle' => 'Answers for merchants and implementers.'])
    <div class="mx-auto max-w-3xl px-4 py-12">
        <ul class="space-y-4 text-sm">
            <li><a href="{{ route('marketing.faq') }}" class="text-teal-dim hover:underline">Frequently asked questions</a></li>
            <li><a href="{{ route('marketing.docs') }}" class="text-teal-dim hover:underline">Documentation</a></li>
            <li><a href="{{ route('marketing.contact') }}" class="text-teal-dim hover:underline">Contact support</a></li>
        </ul>
    </div>
@endsection
