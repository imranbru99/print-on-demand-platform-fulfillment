<section class="border-b border-ink/10 bg-white py-16 dark:border-sand/10 dark:bg-ink-muted/30">
    <div class="mx-auto max-w-6xl px-4">
        <h1 class="font-display text-4xl font-bold">{{ $title }}</h1>
        @isset($subtitle)
            <p class="mt-4 max-w-2xl text-ink/70 dark:text-sand/70">{{ $subtitle }}</p>
        @endisset
    </div>
</section>
