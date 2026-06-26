<div class="space-y-4">
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
        @can('Crear movimientos de libro de bancos')
            
        <x-button icon="clipboard-document-list" href="{{ route('transactions.form', [$this->date, $this->idAccount]) }}"
            class="w-full sm:w-auto">
            Agregar movimiento
        </x-button>
        @endcan

    </div>
    <div class="flex flex-col sm:flex-row gap-4 sm:justify-end sm:items-end">
        @can('PDF libro de bancos')
            <x-button color="red" icon="document-arrow-down"
                href="{{ route('transaction.account.pdf', [$this->start, $this->end, $this->idAccount]) }}">
                Descargar pdf
            </x-button>
        @endcan
        @can('Excel libro de bancos')
            <x-button href="" icon="document-arrow-up" color="emerald" outline class="w-full sm:w-auto">
                Descargar excel
            </x-button>
        @endcan
    </div>

    <div class="overflow-hidden dark:ring-dark-600 rounded-lg shadow ring-1 ring-gray-300">
        <div class="relative soft-scrollbar overflow-auto">
            <svg class="text-primary-500 dark:text-dark-300 absolute bottom-0 left-0 right-0 top-0 m-auto grid h-10 w-10 animate-spin place-items-center"
                wire:loading="quantity,search" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>

            <table class="dark:divide-dark-500/50 min-w-full divide-y divide-gray-200"
                wire:loading.class="cursor-not-allowed select-none opacity-25">

                <thead class="uppercase bg-gray-50 dark:bg-dark-600">
                    <tr>
                        <th scope="col" colspan="4"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            CUENTA: {{ $this->account }}
                        </th>
                        <th scope="col" colspan="4"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            MONEDA: {{ $this->moneda }}
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" colspan="8"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            PERIODO: {{ $this->dateT }}
                        </th>
                    </tr>
                    <tr>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Nº
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Fecha
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            DESCRIPCIÓN
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            DCTO. REF.
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            DEBE
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            HABER
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            SALDO
                        </th>
                        <th scope="col"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Opciones
                        </th>
                    </tr>
                </thead>
                <tbody class="dark:bg-dark-700 dark:divide-dark-500/20 divide-y divide-gray-200 bg-white">
                    @foreach ($transactions as $transaction)
                        <tr class=" " wire:key="8b700ca168f875b5e2a3c9329ba84d55">
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $transaction->date }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $transaction->description }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $transaction->transaction->formatted_last_number }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $transaction->type == 'D' ? $transaction->amount : ($transaction->type == 'B' ? $transaction->amount : '') }}

                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $transaction->type == 'C' ? $transaction->amount : '' }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $transaction->balance }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if ($transaction->type != 'B')
                                    <div class="flex gap-1">
                                        @can('Ver movimiento de libro de bancos ')
                                            
                                        <x-button.circle icon="document" color="red" outline
                                        href="{{ route('receipt.transaction.pdf', $transaction->id) }}" />
                                        @endcan
                                        @can('Editar movimientos de libro de bancos')
                                            
                                        <x-button.circle icon="pencil" color="blue" light
                                        href="{{ route('transactions.form', [$this->date, $this->idAccount, $transaction->id]) }}" />
                                        @endcan
                                        @can('Eliminar movimientos de libro de bancos')
                                            
                                        <x-button.circle icon="trash" color="red" light
                                        onclick="confirmDelete('{{ $transaction->id }}')" />
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination Navigation">
            {{ $transactions->links() }}
        </nav>
    </div>

    <div x-on:close-modal.window="$tsui.close.modal('modal-id')">
        <x-modal title="modal-id" id="modal-id" persistent>
            <form id="user-create" wire:submit="save" class="space-y-2">
                <div class="w-full ">
                    <x-input label="Ingrese el monto" placeholder="Monto" wire:model="amount" />
                </div>
                <div class="w-full">
                    <x-input label="DCTO. REF." placeholder="Nro de documento" wire:model="doc" />
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

</div>
