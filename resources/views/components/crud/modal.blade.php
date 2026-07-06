@props([
    'entity' => '',
    'title' => null,
    'edit' => false,
    'modalId' => 'crud-modal',
    'saveMethod' => null,
    'storeMethod' => 'store',
    'updateMethod' => 'update',
])

@php
    $method = $saveMethod ?? ($edit ? $updateMethod : $storeMethod);
    $label = $title ?? $entity;
@endphp

<div x-on:close-modal.window="$tsui.close.modal('{{ $modalId }}')">
    <x-modal :id="$modalId" persistent>
        <x-slot:header>
            <span class="font-semibold text-base">{{ $edit ? 'Actualizar' : 'Crear' }} | {{ $label }}</span>
        </x-slot:header>
        <div class="space-y-2">
            {{ $slot }}
        </div>
        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button color="outline" type="button" wire:click="clear()" icon="x-mark">Cancelar</x-button>
                <x-button color="primary" wire:click="{{ $method }}" wire:loading.attr="disabled" wire:target="{{ $method }}" icon="check">
                    <span wire:loading.remove wire:target="{{ $method }}">
                        {{ $edit ? 'Actualizar' : 'Guardar' }}
                    </span>
                    <span wire:loading wire:target="{{ $method }}" class="flex items-center gap-2">
                        <x-ui.loading-spinner class="h-4 w-4" />
                        Guardando...
                    </span>
                </x-button>
            </div>
        </x-slot:footer>
    </x-modal>
</div>
