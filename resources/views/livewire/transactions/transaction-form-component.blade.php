<div class="space-y-4">
    <x-card>
        <x-slot:header>
            <span class="font-semibold text-base">Registro de Movimiento</span>
        </x-slot:header>
        <form id="user-create" wire:submit="{{ $this->id ? 'update' : 'store' }}" class="space-y-2">
            <div class="w-full">
                <livewire:supplier-search :ci="$ci" />
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="Nombre y Apellido" wire:model="full_name" placeholder="Nombre completo" />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-input label="Teléfono" wire:model="phone" placeholder="Teléfono" />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="Ingrese el monto" placeholder="Monto" wire:model="amount" />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-select.styled label="Método de pago" :options="[['label' => 'Cheque', 'value' => 'CH'], ['label' => 'Transferencia', 'value' => 'T']]" wire:model='payment_type' />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-input label="{{ $payment_type === 'T' ? 'Nro de transferencia' : 'Nro de cheque' }}" placeholder="{{ $payment_type === 'T' ? 'Nro de transferencia' : 'Nro de cheque' }}" wire:model="number_check" />
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-1/2">
                    <x-select.styled label="Seleccione tipo" :options="[['label' => 'Debe', 'value' => 'D'], ['label' => 'Haber', 'value' => 'C']]" wire:model='type' />
                </div>
                <div class="w-full sm:w-1/2">
                    <x-date label="Seleccione una fecha" wire:model="date" />
                </div>
            </div>
            <div class="w-full">
                <x-textarea wire:model="description" label="Descripción" rows="5" />
            </div>
            <div class="flex flex-col sm:flex-row gap-4 sm:justify-end sm:items-end">
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
            </div>
        </form>
    </x-card>
</div>