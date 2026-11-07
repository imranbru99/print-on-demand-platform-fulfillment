@extends('layouts.marketing')
@section('title', 'Contact')
@section('content')
    @include('marketing.partials.page-hero', ['title' => 'Contact', 'subtitle' => 'We respond within one business day.'])
    <form method="POST" action="{{ route('marketing.contact.submit') }}" class="mx-auto max-w-lg px-4 py-12 space-y-4">
        @csrf
        <div>
            <label class="text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border border-ink/20 bg-white px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border border-ink/20 bg-white px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        </div>
        <div>
            <label class="text-sm font-medium">Message</label>
            <textarea name="message" rows="5" required class="mt-1 w-full rounded-lg border border-ink/20 bg-white px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn-primary">Send message</button>
    </form>
@endsection
