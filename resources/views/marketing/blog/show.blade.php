@extends('layouts.marketing')
@section('title', $post->title)
@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16">
        <p class="text-sm text-ink/50">{{ optional($post->published_at)->format('F j, Y') }}</p>
        <h1 class="mt-2 font-display text-4xl font-bold">{{ $post->title }}</h1>
        <div class="prose mt-8 dark:prose-invert">{!! nl2br(e($post->body)) !!}</div>
    </article>
@endsection
