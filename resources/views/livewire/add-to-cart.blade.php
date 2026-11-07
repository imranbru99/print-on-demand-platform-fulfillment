<div class="flex items-center gap-2">
    <input type="number" wire:model="quantity" min="1" max="99" class="w-16 rounded-lg border border-ink/20 px-2 py-1 text-sm dark:border-sand/20 dark:bg-ink-muted">
    <button type="button" wire:click="add" class="btn-primary">Add to cart</button>
</div>
