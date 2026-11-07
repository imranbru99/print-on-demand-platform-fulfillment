@extends('layouts.marketing')

@section('title', 'Home')

@section('content')
    <section class="pod-gradient-hero relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen overflow-hidden text-sand">
        <div class="pod-grid-pattern pointer-events-none absolute inset-0 opacity-40"></div>
        <div class="animate-glow-pulse pointer-events-none absolute -right-20 top-20 h-64 w-64 rounded-full bg-teal/30"></div>
        <div class="relative mx-auto max-w-6xl px-4 pb-24 pt-20 lg:pb-32 lg:pt-28">
            <p class="animate-fade-up text-sm font-medium uppercase tracking-[0.2em] text-teal">Print-on-demand operations</p>
            <h1 class="animate-fade-up mt-4 max-w-3xl font-display text-4xl font-bold leading-tight md:text-6xl" style="animation-delay: 0.1s">
                Launch stores. Customize products. Fulfill with confidence.
            </h1>
            <p class="animate-fade-up mt-6 max-w-xl text-lg text-sand/80" style="animation-delay: 0.2s">
                A modular SaaS for catalog, design, checkout validation, production routing, and shipping—built for teams who need control without the clutter.
            </p>
            <div class="animate-fade-up mt-10 flex flex-wrap gap-4" style="animation-delay: 0.3s">
                <a href="{{ route('register') }}" class="btn-primary">Start 14-day trial</a>
                <a href="{{ route('marketing.request-demo') }}" class="btn-ghost border-sand/30 text-sand hover:border-teal hover:text-teal">Request a walkthrough</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-20">
        <h2 class="font-display text-3xl font-semibold">How it works</h2>
        <ol class="mt-10 grid gap-8 md:grid-cols-3">
            @foreach ([
                ['Connect your brand', 'Create a tenant, enable modules, and open one or many storefronts.'],
                ['Merchandise with intent', 'Publish catalog items, variants, and print areas with server-side pricing.'],
                ['Fulfill automatically', 'Route orders to production, shipping adapters, and tracking in one flow.'],
            ] as $i => $step)
                <li class="animate-float surface-card" style="animation-delay: {{ $i * 0.4 }}s">
                    <span class="text-xs font-bold text-teal-dim">Step {{ $i + 1 }}</span>
                    <h3 class="mt-2 font-display text-xl font-semibold">{{ $step[0] }}</h3>
                    <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $step[1] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="bg-white py-20 dark:bg-ink-muted/40">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <h2 class="font-display text-3xl font-semibold">Platform features</h2>
                <a href="{{ route('marketing.features.index') }}" class="text-sm font-medium text-teal-dim hover:underline">View all modules →</a>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (collect(config('pod.modules'))->only(config('pod.marketing_features')) as $key => $module)
                    <a href="{{ route('marketing.features.show', $key) }}" class="surface-card group transition hover:-translate-y-1 hover:border-teal/40">
                        <h3 class="font-display text-lg font-semibold group-hover:text-teal-dim">{{ $module['name'] }}</h3>
                        <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $module['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-20">
        <h2 class="font-display text-3xl font-semibold">Built for product-led brands</h2>
        <p class="mt-4 max-w-2xl text-ink/70 dark:text-sand/70">Enable only the modules you need—catalog, design studio, payments, fulfillment—without replatforming when you grow.</p>
        <div class="mt-10 flex flex-wrap gap-3">
            @foreach (['Multi-store', 'Decimal-safe pricing', 'Sandbox payments', 'Webhook-ready', 'Role-based access'] as $tag)
                <span class="rounded-full border border-teal/30 bg-teal/10 px-4 py-1 text-sm">{{ $tag }}</span>
            @endforeach
        </div>
    </section>

    <section class="border-y border-ink/10 bg-mist py-20 dark:border-sand/10 dark:bg-ink">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="font-display text-3xl font-semibold">Pricing preview</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @forelse ($plans as $plan)
                    <div class="surface-card flex flex-col">
                        <h3 class="font-display text-xl font-semibold">{{ $plan->name }}</h3>
                        <p class="mt-4 text-3xl font-bold">{{ $plan->currency ?? 'USD' }} {{ number_format((float) $plan->price, 0) }}<span class="text-base font-normal text-ink/60">/mo</span></p>
                        <ul class="mt-4 flex-1 space-y-2 text-sm text-ink/70 dark:text-sand/70">
                            @foreach ((array) ($plan->features ?? []) as $feature)
                                <li>• {{ is_string($feature) ? $feature : json_encode($feature) }}</li>
                            @endforeach
                            @if (empty($plan->features))
                                <li>• Core modules included</li>
                                <li>• Sandbox payment environment</li>
                            @endif
                        </ul>
                        <a href="{{ route('register') }}" class="btn-primary mt-6 text-center">Choose plan</a>
                    </div>
                @empty
                    @foreach ([['Starter', 49], ['Growth', 149], ['Scale', 399]] as [$name, $price])
                        <div class="surface-card">
                            <h3 class="font-display text-xl font-semibold">{{ $name }}</h3>
                            <p class="mt-4 text-3xl font-bold">USD {{ $price }}<span class="text-base font-normal">/mo</span></p>
                            <p class="mt-4 text-sm text-ink/70 dark:text-sand/70">Configurable when plans are seeded in billing.</p>
                            <a href="{{ route('register') }}" class="btn-primary mt-6 inline-block">Get started</a>
                        </div>
                    @endforeach
                @endforelse
            </div>
            <p class="mt-6 text-center text-sm"><a href="{{ route('marketing.pricing') }}" class="text-teal-dim hover:underline">Compare plans in detail</a></p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-20">
        <h2 class="font-display text-3xl font-semibold">FAQ</h2>
        <div class="mt-8 divide-y divide-ink/10 dark:divide-sand/10" x-data>
            @foreach ([
                ['Do you support custom domains?', 'Store domains are modeled in the platform; path-based storefronts work today with domain mapping ready for production.'],
                ['How are payments handled?', 'Use the sandbox gateway in demo environments; swap adapters for live providers without changing checkout validation.'],
                ['Can I disable modules?', 'Yes. Module middleware gates routes and features per tenant subscription.'],
            ] as [$q, $a])
                <details class="group py-4">
                    <summary class="cursor-pointer font-medium marker:content-none group-open:text-teal-dim">{{ $q }}</summary>
                    <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">{{ $a }}</p>
                </details>
            @endforeach
        </div>
        <div class="mt-12 rounded-2xl bg-ink px-8 py-10 text-center text-sand">
            <h2 class="font-display text-2xl font-semibold">Ready to open your first store?</h2>
            <p class="mt-2 text-sand/80">Register your business workspace in minutes.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-primary">Create account</a>
                <a href="{{ route('marketing.contact') }}" class="btn-ghost border-sand/30 text-sand">Talk to us</a>
            </div>
        </div>
    </section>
@endsection

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@endpush
