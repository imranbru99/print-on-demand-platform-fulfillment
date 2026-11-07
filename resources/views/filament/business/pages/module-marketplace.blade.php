<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($this->modules as $module)
            <x-filament::section>
                <x-slot name="heading">
                    {{ $module['name'] }}
                </x-slot>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $module['description'] ?: 'No description provided.' }}
                </p>

                <div class="mt-4 flex items-center justify-between">
                    <x-filament::badge :color="$module['enabled'] ? 'success' : 'gray'">
                        {{ $module['enabled'] ? 'Enabled' : 'Disabled' }}
                    </x-filament::badge>

                    <x-filament::button
                        wire:click="toggle('{{ $module['key'] }}')"
                        :color="$module['enabled'] ? 'danger' : 'primary'"
                        size="sm"
                    >
                        {{ $module['enabled'] ? 'Disable' : 'Enable' }}
                    </x-filament::button>
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
