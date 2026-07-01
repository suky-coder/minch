<div class="space-y-4">
    <div class="flex items-center gap-2">
        <x-button color="outline" icon="arrow-left" href="{{ route('accounts') }}" wire:navigate>
            Volver
        </x-button>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div class="flex gap-2">
            <x-date month-year-only wire:model="selectedDate" />
            <x-button color="sky" icon="magnifying-glass-circle" outline wire:click="$refresh">Consultar</x-button>
        </div>
        <div class="flex gap-2">
            <x-button href="{{ route('accounts.box.form', ['date_account' => $selectedDate . '-01']) }}" icon="clipboard-document-list">
                Agregar movimiento
            </x-button>
            <x-button color="red" icon="document-arrow-down" href="{{ route('account.box.pdf') }}" target="_blank">
                Descargar PDF
            </x-button>
        </div>
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
                    @foreach ($movements as $movement)
                        <tr class=" " wire:key="8b700ca168f875b5e2a3c9329ba84d55">
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $movement->type == 'B' ? '' : ($movement->box?->number_label ?? '') }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $movement->date }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $movement->description }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $movement->type == 'D' ? number_format($movement->amount, 2, '.', ',') : ($movement->type == 'B' ? number_format($movement->amount, 2, '.', ',') : '') }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $movement->type == 'C' ? number_format($movement->amount, 2, '.', ',') : '' }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ number_format($movement->balance, 2, '.', ',') }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if ($movement->type != 'B')
                                    <div class="flex gap-1">
                                        <x-button.circle icon="document" color="red" outline href="{{ route('receipt.box.pdf', $movement->id) }}" />

                                        <x-button.circle icon="pencil" color="blue" light
                                            wire:click="$dispatch('load::movement', { 'movement' : '{{ $movement->id }}'})" />
                                        <x-button.circle icon="trash" color="red" light
                                            onclick="confirmDelete('{{ $movement->id }}')" />
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
            {{ $movements->links() }}
        </nav>
    </div>

</div>
