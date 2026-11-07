@extends('layouts.marketing')
@section('title', 'FAQ')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'FAQ', 'subtitle' => 'Common questions about stores, billing, and fulfillment.'])
    <div class="mx-auto max-w-3xl divide-y divide-ink/10 px-4 py-8 dark:divide-sand/10">
        @forelse ($faqs as $faq)
            <details class="py-4">
                <summary class="cursor-pointer font-medium">{{ $faq->question }}</summary>
                <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $faq->answer }}</p>
            </details>
        @empty
            @foreach ($fallbackFaqs as $item)
                <details class="py-4">
                    <summary class="cursor-pointer font-medium">{{ $item['q'] }}</summary>
                    <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $item['a'] }}</p>
                </details>
            @endforeach
        @endforelse
    </div>
@endsection
