<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — Print-on-Demand Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    <x-demo-banner />
    @include('partials.marketing.nav')

    <main class="flex-1">
        @if (session('success'))
            <div class="mx-auto max-w-6xl px-4 pt-4">
                <p class="rounded-lg border border-teal/40 bg-teal/10 px-4 py-2 text-sm text-ink dark:text-sand">{{ session('success') }}</p>
            </div>
        @endif
        @yield('content')
    </main>

    @include('partials.marketing.footer')

    @livewireScripts
    @stack('scripts')
</body>
</html>
