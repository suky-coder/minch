<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="{{ $this->supplierId ? 'Actualizar' : 'Crear' }} | Proveedor" id="modal-id" persistent>
        <form id="supplier-form" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre Completo" placeholder="Nombre completo" wire:model="full_name" />
            </div>
            <div class="w-full">
                <x-input label="Cédula de Identidad" placeholder="C.I." wire:model="ci" />
            </div>
            <div class="w-full">
                <x-input label="Descripción" placeholder="Descripción del proveedor" wire:model="description" />
            </div>
        </form>
        <x-slot:footer>
            <x-button color="outline" type="button" wire:click="clear()">
                Cancelar
            </x-button>
            @if ($this->supplierId)
                <x-button color="primary" wire:click="update()">
                    Actualizar
                </x-button>
            @else
                <x-button color="primary" wire:click="store()">
                    Guardar
                </x-button>
            @endif
        </x-slot:footer>
    </x-modal>
</div>