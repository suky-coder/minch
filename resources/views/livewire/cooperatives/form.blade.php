<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="modal-id" id="modal-id" persistent>
        <form id="user-create" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre" placeholder="Nombre" wire:model="name" />
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="Ingrese el numero de NIT" type="number" placeholder="NIT"
                        wire:model="NIT" />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-input label="Ingrese el numero de NIM" type="text" placeholder="NIM"
                        wire:model="NIM" />
                </div>
            </div>


            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="Ingrese la concesión" placeholder="Concesión" wire:model="concession" />
                </div> <div class="w-full sm:w-1/2">
                    <x-input label="Ingrese la vocamina" placeholder="Vocamina" wire:model="mine" />
                </div>
            </div>
             <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/3">
                    <x-input label="Ingrese el municipio" placeholder="Municipio" wire:model="municipality" />
                </div> 
                <div class="w-full sm:w-1/3">
                    <x-input label="Ingrese el Aporte a Coop." placeholder="Aporte a Coop." wire:model="contribution" />
                </div>
                <div class="w-full sm:w-1/3">
                    <x-input label="Ingrese el Aporte a Comibol." placeholder="Aporte a Comibol." wire:model="comibol" />
                </div>
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
