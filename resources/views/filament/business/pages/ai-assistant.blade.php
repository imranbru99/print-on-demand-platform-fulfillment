<x-filament-panels::page>
    <form wire:submit="ask" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit">
            Send prompt
        </x-filament::button>
    </form>

    @if ($reply)
        <x-filament::section class="mt-6">
            <x-slot name="heading">Response</x-slot>
            <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap text-sm">
                {{ $reply }}
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
