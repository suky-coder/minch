<div class="space-y-3">

    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
        <x-button href="{{ route('retention.form') }}" icon="clipboard-document-list"
            x-on:click="$tsui.open.modal('modal-id')" class="w-full sm:w-auto">
            Agregar retención
        </x-button>
        <x-button href="{{ route('retention.month.excel', [$this->selectedDate, $this->type]) }}" icon="document-arrow-up"
            x-on:click="$tsui.open.modal('modal-id')" color="emerald" outline class="w-full sm:w-auto">
            Descargar excel
        </x-button>
    </div>
    <div class="flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-end">

        <div class="flex flex-col sm:flex-row gap-4 sm:justify-start sm:items-end">
            <x-select.styled wire:model="type" :options="[['name' => 'Servicios', 'id' => 'S'], ['name' => 'Bienes', 'id' => 'G']]" select="label:name|value:id" required />
            <x-date month-year-only wire:model="selectedDate" />
        </div>
        <div>

            <x-button color="sky" icon="magnifying-glass-circle" class="w-full sm:w-auto" outline
                wire:click.prevent="$refresh">Consultar</x-button>
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
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Nro
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Fecha
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Nombre y Apellido
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            ROS
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Cédula de Identidad
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            {{ $this->type == 'S' ? 'Servicio' : 'Bien' }}
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Monto
                        </th>
                        <th scope="col" colspan="{{ $taxes->count() }}"
                            class="dark:text-dark-200 px-3 py-3.5 text-center text-sm font-semibold text-gray-700">
                            Retenciones
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Importe a cancelar
                        </th>
                        <th scope="col" rowspan="3"
                            class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                            Opciones
                        </th>
                    </tr>

                    <tr>
                        @foreach ($taxes as $taxe)
                            <th scope="col"
                                class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                                {{ $taxe->initials }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($taxes as $taxe)
                            <th scope="col"
                                class="dark:text-dark-200 px-3 py-3.5 text-left text-sm font-semibold text-gray-700">
                                {{ $taxe->number }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="dark:bg-dark-700 dark:divide-dark-500/20 divide-y divide-gray-200 bg-white">
                    @foreach ($retentions as $retention)
                        <tr class=" " wire:key="8b700ca168f875b5e2a3c9329ba84d55">
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->date }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->supplier->full_name }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->code }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->supplier->ci }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->summary }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->amount }}
                            </td>
                            @foreach ($retention->discounts as $discount)
                                <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $discount->amount }}
                                </td>
                            @endforeach
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $retention->calculate_total }}
                            </td>
                            <td class="dark:text-dark-300 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <div class="flex gap-1">
                                    <x-button.circle icon="document" color="red" outline
                                        href="{{ route('retention.pdf.form', $retention->id) }}" target="_blank" />
                                    <x-button.circle icon="pencil" color="blue" light
                                        href="{{ route('retention.form', $retention->id) }}" />
                                    <x-button.circle icon="trash" color="red" light
                                        onclick="confirmDelete('{{ $retention->id }}')" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
