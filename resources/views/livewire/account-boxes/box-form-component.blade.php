<div class="space-y-4">
    <x-card>
        <x-slot:header>
            <span class="font-semibold text-base">Registro de Movimiento de Caja</span>
        </x-slot:header>
        <form id="box-form" wire:submit="{{ $this->id ? 'update' : 'store' }}" class="space-y-2">
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
                <x-button color="outline" type="button" wire:click="clear()">
                    Cancelar
                </x-button>
                @if ($this->id)
                    <x-button color="primary" type="submit">
                        Actualizar
                    </x-button>
                @else
                    <x-button color="primary" type="submit">
                        Guardar
                    </x-button>
                @endif
            </div>
        </form>
    </x-card>
</div>