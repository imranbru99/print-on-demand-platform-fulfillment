<header
    x-data="{ open: false }"
    class="sticky top-0 z-40 border-b border-white/10 bg-ink/80 text-sand backdrop-blur-lg"
>
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4">
        <a href="{{ route('marketing.home') }}" class="font-display text-lg font-semibold tracking-tight">
            {{ config('app.name', 'POD Platform') }}
        </a>
        <nav class="hidden items-center gap-6 text-sm md:flex">
            <a href="{{ route('marketing.features.index') }}" class="opacity-90 hover:text-teal">Features</a>
            <a href="{{ route('marketing.pricing') }}" class="opacity-90 hover:text-teal">Pricing</a>
            <a href="{{ route('marketing.integrations') }}" class="opacity-90 hover:text-teal">Integrations</a>
            <a href="{{ route('marketing.docs') }}" class="opacity-90 hover:text-teal">Docs</a>
            <a href="{{ route('marketing.blog.index') }}" class="opacity-90 hover:text-teal">Blog</a>
        </nav>
        <div class="hidden items-center gap-3 md:flex">
            <button type="button" class="rounded-full p-2 opacity-80 hover:opacity-100" onclick="podToggleTheme()" aria-label="Toggle theme">
                <span class="dark:hidden">◐</span>
                <span class="hidden dark:inline">◑</span>
            </button>
            @auth
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="btn-ghost border-sand/30 text-sand">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-sand/90 hover:text-teal">Log in</a>
                <a href="{{ route('register') }}" class="btn-primary">Start free trial</a>
            @endauth
        </div>
        <button type="button" class="md:hidden text-sand" @click="open = !open" aria-label="Menu">☰</button>
    </div>
    <div x-show="open" x-transition class="border-t border-white/10 px-4 py-4 md:hidden">
        <nav class="flex flex-col gap-3 text-sm">
            <a href="{{ route('marketing.features.index') }}">Features</a>
            <a href="{{ route('marketing.pricing') }}">Pricing</a>
            <a href="{{ route('marketing.contact') }}">Contact</a>
            <a href="{{ route('login') }}">Log in</a>
            <a href="{{ route('register') }}" class="btn-primary w-fit">Register</a>
        </nav>
    </div>
</header>
