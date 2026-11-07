<form wire:submit="submit" class="mt-8 grid gap-8 lg:grid-cols-2">
    <div class="space-y-4">
        <input wire:model="email" type="email" placeholder="Email" required class="w-full rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        <div class="grid grid-cols-2 gap-3">
            <input wire:model="first_name" placeholder="First name" required class="rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
            <input wire:model="last_name" placeholder="Last name" required class="rounded-lg border border-ink/20 px-3 py-2 dark:border-sand/20 dark:bg-ink-muted">
        </div>
        <p class="text-xs text-ink/60">Sandbox payment — no real card required.</p>
        @if ($error)
            <p class="text-sm text-red-600">{{ $error }}</p>
        @endif
        <input type="hidden" wire:model="client_subtotal">
        <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">Pay {{ number_format((float) $subtotal, 2) }} (sandbox)</button>
    </div>
    <div class="surface-card">
        <h2 class="font-semibold">Order summary</h2>
        <ul class="mt-4 space-y-2 text-sm">
            @foreach ($cart->items as $item)
                <li class="flex justify-between"><span>{{ $item->product?->name }}</span><span>{{ number_format((float) $item->unit_price * $item->quantity, 2) }}</span></li>
            @endforeach
        </ul>
        <p class="mt-4 border-t border-ink/10 pt-4 font-semibold dark:border-sand/10">Total: {{ number_format((float) $subtotal, 2) }}</p>
    </div>
</form>
