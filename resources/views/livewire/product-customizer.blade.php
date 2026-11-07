<div
    x-data="{
        layers: @entangle('layers'),
        selected: 0,
        onFile(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => $wire.addLayer(reader.result);
            reader.readAsDataURL(file);
        },
    }"
    class="mt-8 grid gap-8 lg:grid-cols-[1fr_280px]"
>
    <div class="relative aspect-square overflow-hidden rounded-2xl border border-ink/15 bg-sand/30 dark:border-sand/15 dark:bg-ink-muted">
        <template x-for="(layer, index) in layers" :key="layer.id">
            <img
                :src="layer.src"
                class="absolute cursor-move select-none"
                :style="`left:${layer.x}px;top:${layer.y}px;width:${layer.width}px;height:${layer.height}px;transform:rotate(${layer.rotate}deg)`"
                @mousedown.prevent="selected = index"
                draggable="false"
            >
        </template>
        <p class="absolute bottom-4 left-4 text-xs text-ink/50" x-show="layers.length === 0">Upload artwork to begin</p>
    </div>
    <aside class="surface-card space-y-4">
        <label class="block text-sm font-medium">Upload layer</label>
        <input type="file" accept="image/*" @change="onFile($event)" class="text-sm">
        <div class="space-y-2 text-sm" x-show="layers[selected]">
            <label class="block">Width <input type="range" min="40" max="400" x-model.number="layers[selected].width" class="w-full"></label>
            <label class="block">Height <input type="range" min="40" max="400" x-model.number="layers[selected].height" class="w-full"></label>
            <label class="block">Rotate <input type="range" min="0" max="360" x-model.number="layers[selected].rotate" class="w-full"></label>
            <button type="button" class="text-red-600 text-xs" @click="$wire.removeLayer(selected)">Remove layer</button>
        </div>
        <button type="button" wire:click="addToCart" class="btn-primary w-full">Add customized item to cart</button>
    </aside>
</div>

@assets
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@endassets
