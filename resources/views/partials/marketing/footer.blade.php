<footer class="border-t border-ink/10 bg-white dark:border-sand/10 dark:bg-ink-muted">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 md:grid-cols-4">
        <div>
            <p class="font-display text-lg font-semibold">{{ config('app.name') }}</p>
            <p class="mt-2 text-sm text-ink/70 dark:text-sand/70">Operate custom product stores with validated checkout and automated fulfillment.</p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-ink/50">Product</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('marketing.features.index') }}" class="hover:text-teal-dim">Features</a></li>
                <li><a href="{{ route('marketing.pricing') }}" class="hover:text-teal-dim">Pricing</a></li>
                <li><a href="{{ route('marketing.integrations') }}" class="hover:text-teal-dim">Integrations</a></li>
            </ul>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-ink/50">Resources</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('marketing.docs') }}" class="hover:text-teal-dim">Documentation</a></li>
                <li><a href="{{ route('marketing.api-docs') }}" class="hover:text-teal-dim">API</a></li>
                <li><a href="{{ route('marketing.help') }}" class="hover:text-teal-dim">Help center</a></li>
                <li><a href="{{ route('marketing.faq') }}" class="hover:text-teal-dim">FAQ</a></li>
            </ul>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-ink/50">Company</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('marketing.about') }}" class="hover:text-teal-dim">About</a></li>
                <li><a href="{{ route('marketing.contact') }}" class="hover:text-teal-dim">Contact</a></li>
                <li><a href="{{ route('marketing.privacy') }}" class="hover:text-teal-dim">Privacy</a></li>
                <li><a href="{{ route('marketing.terms') }}" class="hover:text-teal-dim">Terms</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-ink/10 px-4 py-4 text-center text-xs text-ink/50 dark:border-sand/10">
        © {{ date('Y') }} {{ config('app.name') }}. <a href="{{ route('marketing.refund-policy') }}" class="underline">Refund policy</a>
    </div>
</footer>
