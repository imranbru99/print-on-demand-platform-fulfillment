@extends('layouts.marketing')
@section('title', 'Blog')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Blog', 'subtitle' => 'Product updates and implementation notes.'])
    <div class="mx-auto max-w-6xl px-4 py-12">
        @forelse ($posts as $post)
            <article class="border-b border-ink/10 py-6 dark:border-sand/10">
                <a href="{{ route('marketing.blog.show', $post->slug) }}" class="font-display text-xl font-semibold hover:text-teal-dim">{{ $post->title }}</a>
                <p class="mt-1 text-xs text-ink/50">{{ optional($post->published_at)->format('M j, Y') }}</p>
            </article>
        @empty
            <p class="text-ink/70">No published posts yet. Publish from the CMS module.</p>
        @endforelse
        <div class="mt-8">{{ $posts->links() }}</div>
    </div>
@endsection
