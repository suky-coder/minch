<div class="space-y-4">
    <div class="flex items-center gap-2">
        <x-button color="outline" icon="arrow-left" href="{{ route('accounts.statement') }}" wire:navigate>
            Volver
        </x-button>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-end">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $holderLabel }}</p>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $holder->full_name }}</h1>
            <p class="text-sm text-gray-500">CI: {{ $holder->ci }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            @can('Crear estados de cuenta')
                <x-button icon="plus" x-on:click="$tsui.open.modal('modal-id')" class="w-full sm:w-auto">
                    Agregar movimiento
                </x-button>
            @endcan
            @can('PDF estados de cuenta')
                <x-button color="red" icon="document-arrow-down"
                    href="{{ route('account.statement.pdf', ['type' => $this->holderType, 'id' => $this->holderId]) }}"
                    target="_blank">
                    Descargar PDF
                </x-button>
            @endcan
        </div>
    </div>

    <div class="overflow-hidden dark:ring-dark-600 rounded-lg shadow ring-1 ring-gray-300">
        <div class="relative soft-scrollbar overflow-auto">
            <table class="dark:divide-dark-500/50 min-w-full divide-y divide-gray-200">
                <thead class="uppercase bg-gray-50 dark:bg-dark-600">
                    <tr>
                        <th colspan="9" class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Nombre: {{ $holder->full_name }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5" class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Debe: {{ $holder->amountD }}
                        </th>
                        <th colspan="4" class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Haber: {{ $holder->amountC }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="9" class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Saldo: {{ $holder->balance }}
                        </th>
                    </tr>
                    <tr>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Nº</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Fecha</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Descripción</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Dcto. Ref.</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Nº Volq.</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Debe</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Haber</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Saldo</th>
                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-700">Opciones</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-dark-700 dark:divide-dark-500/20 divide-y divide-gray-200 bg-white">
                    @forelse ($accountStatements as $movement)
                        @php
                            $documentRef = $movement->document
                                ?? $movement->box?->number_label
                                ?? $movement->transaction?->number_label
                                ?? '';
                            $isManual = ! $movement->box && ! $movement->transaction && $movement->type !== 'B';
                        @endphp
                        <tr wire:key="movement-{{ $movement->id }}">
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->date }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->description }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $documentRef }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">{{ $movement->number_vol }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                {{ in_array($movement->type, ['D', 'B']) ? number_format($movement->amount, 2, '.', ',') : '' }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                {{ $movement->type === 'C' ? number_format($movement->amount, 2, '.', ',') : '' }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                {{ number_format($movement->balance, 2, '.', ',') }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500">
                                @if ($movement->type !== 'B')
                                    <div class="flex gap-1">
                                        @if ($movement->box)
                                            <x-button.circle icon="document" color="red" outline
                                                href="{{ route('receipt.box.pdf', $movement->id) }}" target="_blank" />
                                        @elseif ($movement->transaction)
                                            <x-button.circle icon="document" color="red" outline
                                                href="{{ route('receipt.transaction.pdf', $movement->id) }}" target="_blank" />
                                        @endif

                                        @if ($isManual)
                                            @can('Editar estados de cuenta')
                                                <x-button.circle icon="pencil" color="blue" light
                                                    wire:click="$dispatch('load::movement', { movement: {{ $movement->id }} })" />
                                            @endcan
                                            @can('Eliminar estados de cuenta')
                                                <x-button.circle icon="trash" color="red" light
                                                    wire:click="delete({{ $movement->id }})"
                                                    wire:confirm="¿Está seguro de eliminar este movimiento?" />
                                            @endcan
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-8 text-center text-sm text-gray-500">
                                No hay movimientos registrados para este {{ strtolower($holderLabel) }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $accountStatements->links() }}
    </div>

    <div x-on:close-modal.window="$tsui.close.modal('modal-id')">
        <x-modal title="modal-id" id="modal-id" persistent>
            <x-slot:header>
                <span class="font-semibold text-base">{{ $this->id ? 'Actualizar' : 'Agregar' }} movimiento</span>
            </x-slot:header>
            <form wire:submit.prevent="{{ $this->id ? 'update' : 'store' }}" class="space-y-2">
                <x-input label="Monto" placeholder="Monto" wire:model="amount" />
                <div class="flex flex-col sm:flex-row gap-4">
                    <x-input label="Dcto. Ref." placeholder="Nro. de documento" wire:model="doc" />
                    <x-input label="Nº Volq." placeholder="Nro. de volqueta" wire:model="vol" />
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <x-select.styled label="Tipo" :options="[['label' => 'Debe', 'value' => 'D'], ['label' => 'Haber', 'value' => 'C']]" wire:model="type" />
                    <x-date label="Fecha" wire:model="date" />
                </div>
                <x-textarea wire:model="description" label="Descripción" rows="4" />
            </form>
            <x-slot:footer>
                <x-button color="outline" type="button" wire:click="clear()">Cancelar</x-button>
                @if ($this->id)
                    <x-button color="primary" wire:click="update()">Actualizar</x-button>
                @else
                    <x-button color="primary" wire:click="store()">Guardar</x-button>
                @endif
            </x-slot:footer>
        </x-modal>
    </div>
</div>
