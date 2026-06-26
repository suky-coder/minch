<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="{{ $this->id?'Actualizar':'Crear' }} | Impuesto" id="modal-id" persistent>
        <form id="user-create" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre de impuesto" placeholder="Nombre" wire:model="name" />
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/3">
                    <x-input label="Iniciales" placeholder="Ej. IVA" wire:model="initials" />
                </div>
                <div class="w-full sm:w-1/3">
                    <x-input label="Numero" placeholder="Ej. 404" wire:model="number" type="number" />
                </div>

                <div class="w-full sm:w-1/3">
                    <x-input label="Porcentaje %" placeholder="Ej. 10.50" type="number"
                        wire:model="applied_discount" />
                </div>
            </div>
            <x-select.styled label="Seleccione una opcion" :options="[
                ['label' => 'Todos', 'value' => 'A'],
                ['label' => 'Bienes', 'value' => 'G'],
                ['label' => 'Servicios', 'value' => 'S'],
            ]" wire:model='type' />
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
