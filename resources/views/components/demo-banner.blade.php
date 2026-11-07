@if (config('pod.demo_mode'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="relative z-50 border-b border-amber-500/40 bg-amber-100 px-4 py-2 text-center text-sm text-amber-950 dark:bg-amber-950/80 dark:text-amber-100"
        role="status"
    >
        <span class="font-medium">Demo mode</span> — sandbox payments and sample data. Nothing is charged for real.
        <button type="button" class="ml-3 underline opacity-80 hover:opacity-100" @click="show = false">Dismiss</button>
    </div>
@endif
