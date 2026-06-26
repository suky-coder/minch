<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="modal-id" id="modal-id" persistent>
        <x-slot:header>
            <span class="font-semibold text-base">{{ $this->id ? 'Actualizar' : 'Crear' }} | {{ $holderLabel ?? 'Titular' }}</span>
        </x-slot:header>
        <form id="account-statement-form" wire:submit.prevent="{{ $this->id ? 'update' : 'store' }}" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre Completo" placeholder="Nombre completo" wire:model="full_name" />
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="w-full sm:w-1/2">
                    <x-input label="Cédula de Identidad" placeholder="Ej. 1234567" wire:model="ci" />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-input label="Teléfono" placeholder="Ej. 77731123" wire:model="phone" />
                </div>
            </div>
            <div class="w-full">
                <x-select.styled label="Cooperativa (opcional)" :options="$options" wire:model="cooperative_id" />
            </div>
            <x-upload label="Contrato / documento" accept=".pdf,.doc,.docx"
                wire:model="file" tip="Arrastre el archivo aquí" />
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
