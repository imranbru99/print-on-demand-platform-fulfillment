@extends('layouts.marketing')

@section('title', $page->title)

@section('content')
    <article class="mx-auto max-w-3xl px-4 py-16 prose prose-slate dark:prose-invert">
        <h1 class="font-display">{{ $page->title }}</h1>
        <div class="mt-6 whitespace-pre-line text-ink/80 dark:text-sand/80">{!! nl2br(e($page->body)) !!}</div>
    </article>
@endsection
