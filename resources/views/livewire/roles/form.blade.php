<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="{{ $this->id?'Actualizar':'Crear' }} | Rol" id="modal-id" persistent>
        <form id="user-create" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre del rol" placeholder="Nombre" wire:model="name" />
            </div>
        </form>
        <x-slot:footer>
            <x-button color="outline" type="button" wire:click="clear()">
                Cancelar
            </x-button>
            @if ($this->id)
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
