<div class="space-y-4">
    <x-card>
        <x-slot:header>
            <span class="font-semibold text-base">Registro de Movimiento de Caja</span>
        </x-slot:header>
        <form id="box-form" wire:submit="{{ $this->id ? 'update' : 'store' }}" class="space-y-2" x-on:keydown.cmd.enter="$wire.{{ $this->id ? 'update' : 'store' }}()">
            {{-- Buscador de proveedor --}}
            <div class="w-full">
                <livewire:supplier-search :ci="$ci" />
            </div>

            {{-- Datos de la persona --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input label="Nombre y Apellido" wire:model="full_name" placeholder="Nombre completo" />
                </div>
                <div>
                    <x-input label="Teléfono" wire:model="phone" placeholder="Teléfono" />
                </div>
            </div>

            {{-- Datos del movimiento --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input label="Monto" placeholder="Monto" wire:model="amount" />
                </div>
                @if ($this->id)
    <div class="w-full">
        <x-input label="Número de Caja" value="{{ $movement->box->number ?? '' }}" readonly disabled />
    </div>
@endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-select.styled label="Tipo" :options="[['label' => 'Ingreso', 'value' => 'D'], ['label' => 'Egreso', 'value' => 'C']]" wire:model="type" />
                </div>
                <div>
                    <x-date label="Fecha" wire:model="date" />
                </div>
            </div>

            <div>
                <x-textarea wire:model="description" label="Descripción" rows="3" />
            </div>

            <div class="flex flex-col sm:flex-row gap-4 sm:justify-end sm:items-end">
                <x-button color="outline" type="button" wire:click="clear()" icon="x-mark">
                    Cancelar
                </x-button>
                <x-button color="primary" type="submit" wire:loading.attr="disabled" wire:target="store,update" icon="check">
                    <span wire:loading.remove wire:target="store,update">
                        {{ $this->id ? 'Actualizar' : 'Guardar' }}
                    </span>
                    <span wire:loading wire:target="store,update" class="flex items-center gap-2">
                        <x-ui.loading-spinner class="h-4 w-4" />
                        Guardando...
                    </span>
                </x-button>
            </div>
        </form>
    </x-card>
</div>