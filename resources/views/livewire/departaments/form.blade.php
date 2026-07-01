<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="{{ $this->departamentId ? 'Actualizar' : 'Crear' }} | Departamento" id="modal-id" persistent>
        <form id="departament-form" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Área" placeholder="Nombre del área" wire:model="area" />
            </div>
            <div class="w-full">
                <x-input label="Descripción" placeholder="Descripción del departamento" wire:model="description" />
            </div>
            <div class="w-full">
                <x-select.styled label="Cuenta" wire:model="account_id" :options="\App\Models\Account::select('id as value', 'name as label')->get()->toArray()" />
            </div>
            <div class="w-full">
                <x-select.styled label="Usuario" wire:model="user_id" :options="\App\Models\User::select('id as value', 'name as label')->get()->toArray()" />
            </div>
        </form>
        <x-slot:footer>
            <x-button color="outline" type="button" wire:click="clear()">
                Cancelar
            </x-button>
            @if ($this->departamentId)
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
