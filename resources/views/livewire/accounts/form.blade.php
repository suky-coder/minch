<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="modal-id" id="modal-id" persistent>
        <form id="user-create" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre" placeholder="Nombre" wire:model="name" />
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="Numero de Cuenta" type="number" placeholder="Numero de cuenta"
                        wire:model="account_number" />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-select.styled label="Seleccione el tipo de moneda" :options="[
                        ['label' => 'BOB', 'value' => 'BOB'],
                        ['label' => 'EUR', 'value' => 'EUR'],
                        ['label' => 'USD', 'value' => 'USD'],
                    ]"
                        wire:model='currency_type' />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="Sigla" placeholder="Ej. BNB" wire:model="initials" />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-color wire:model="color" label="Color"/>
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
