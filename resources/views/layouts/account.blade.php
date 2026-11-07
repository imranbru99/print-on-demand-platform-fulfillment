@extends('layouts.storefront')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[220px_1fr]">
        <aside class="surface-card h-fit lg:sticky lg:top-6">
            <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-ink/50 dark:text-sand/50">Your account</p>
            <nav class="flex flex-col gap-2 text-sm">
                @foreach ([
                    'dashboard' => 'Overview',
                    'orders' => 'Orders',
                    'tracking' => 'Tracking',
                    'returns' => 'Returns',
                    'profile' => 'Profile',
                    'addresses' => 'Addresses',
                    'support' => 'Support',
                ] as $route => $label)
                    <a
                        href="{{ route('storefront.account.'.$route, $store->slug) }}"
                        @class([
                            'rounded-lg px-3 py-2 transition',
                            'bg-teal/15 text-ink font-medium dark:text-sand' => request()->routeIs('storefront.account.'.$route) || request()->routeIs('storefront.account.'.$route.'.*'),
                            'hover:bg-ink/5 dark:hover:bg-sand/5' => ! request()->routeIs('storefront.account.'.$route),
                        ])
                    >{{ $label }}</a>
                @endforeach
            </nav>
        </aside>
        <div>
            @yield('account')
        </div>
    </div>
@endsection
