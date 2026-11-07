<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $store->name ?? 'Store')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-mist text-ink dark:bg-ink dark:text-sand">
    <x-demo-banner />
    <header class="border-b border-ink/10 bg-white/70 backdrop-blur-md dark:border-sand/10 dark:bg-ink-muted/80">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
            <a href="{{ route('storefront.home', $store->slug) }}" class="font-display text-xl font-semibold">{{ $store->name }}</a>
            <nav class="flex flex-wrap items-center gap-4 text-sm">
                <a href="{{ route('storefront.shop', $store->slug) }}" class="hover:text-teal-dim">Shop</a>
                <a href="{{ route('storefront.cart', $store->slug) }}" class="hover:text-teal-dim">Cart</a>
                @auth
                    <a href="{{ route('storefront.account.dashboard', $store->slug) }}" class="hover:text-teal-dim">Account</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-teal-dim">Sign in</a>
                @endauth
                @isset($store)
                    <livewire:cart-badge :store-id="$store->id" :key="'cart-badge-'.$store->id" />
                @endisset
            </nav>
        </div>
    </header>

    @if (session('cart_message') || session('success'))
        <div class="mx-auto max-w-6xl px-4 pt-3 text-sm text-teal-dim">
            {{ session('cart_message') ?? session('success') }}
        </div>
    @endif

    <main class="mx-auto max-w-6xl px-4 py-8">
        @yield('content')
    </main>

    <footer class="mt-auto border-t border-ink/10 py-8 text-center text-sm text-ink/60 dark:border-sand/10">
        <p>{{ $store->name }} · Powered by {{ config('app.name') }}</p>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
