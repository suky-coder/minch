<div class="space-y-6">

    {{-- Título --}}
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Retenciones</h2>

    {{-- Header: filtros + acciones --}}
    <div class="bg-dark-800/40 backdrop-blur-sm rounded-xl border border-dark-600/20 p-4 space-y-4">

        <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-end">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                <div class="w-full sm:w-auto sm:min-w-[160px]">
                    <x-select.styled wire:model="type" :options="[['name' => 'Servicios', 'id' => 'S'], ['name' => 'Bienes', 'id' => 'G']]" select="label:name|value:id" required class="w-full!" />
                </div>
                <div class="w-full sm:w-auto sm:min-w-[160px]">
                    <x-date month-year-only wire:model="selectedDate" class="w-full!" />
                </div>
                <div class="w-full sm:w-auto">
                    <x-button color="primary" icon="magnifying-glass-circle" wire:click.prevent="$refresh" class="w-full! sm:w-auto! justify-center">
                        Consultar
                    </x-button>
                </div>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('retention.form') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-500 transition-all duration-200 shadow-lg shadow-primary-600/20 justify-center sm:justify-start">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Agregar retención
                </a>
                <a href="{{ route('retention.month.excel', [$this->selectedDate, $this->type]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-emerald-300 bg-emerald-600/10 border border-emerald-500/20 hover:bg-emerald-600/20 transition-all duration-200 justify-center sm:justify-start">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Descargar Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="rounded-xl bg-dark-800/40 backdrop-blur-sm border border-dark-600/20 overflow-hidden">
        <div class="relative soft-scrollbar overflow-x-auto">
            <x-ui.loading-spinner class="text-primary-500 dark:text-dark-300 absolute bottom-0 left-0 right-0 top-0 m-auto h-10 w-10" wire:loading="quantity,search" />

            <table class="min-w-full divide-y divide-dark-600/10"
                wire:loading.class="cursor-not-allowed select-none opacity-25">

                <thead class="bg-dark-700/50">
                    <tr>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            Nro
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            Fecha
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            Nombre y Apellido
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            ROS
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            Cédula de Identidad
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            {{ $this->type == 'S' ? 'Servicio' : 'Bien' }}
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-dark-300">
                            Monto
                        </th>
                        <th scope="col" colspan="{{ $taxes->count() }}"
                            class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-dark-300 border-b border-dark-600/10">
                            Retenciones
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-primary-400">
                            Importe a cancelar
                        </th>
                        <th scope="col" rowspan="3"
                            class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-dark-300">
                            Opciones
                        </th>
                    </tr>

                    <tr class="bg-dark-700/30">
                        @foreach ($taxes as $taxe)
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-dark-300">
                                {{ $taxe->initials }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-dark-700/20">
                        @foreach ($taxes as $taxe)
                            <th scope="col"
                                class="px-4 py-2 text-center text-[10px] font-mono text-dark-400">
                                {{ $taxe->number }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-600/10">
                    @forelse ($retentions as $retention)
                        <tr class="group transition-all duration-200 hover:bg-primary-500/5"
                            wire:key="8b700ca168f875b5e2a3c9329ba84d55"
                            style="animation: fade-in-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; animation-delay: {{ $loop->index * 0.03 }}s;">
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-mono text-dark-300">
                                {{ $loop->iteration }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-dark-300">
                                {{ $retention->date }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-dark-200 max-w-[180px] truncate" title="{{ $retention->supplier->full_name }}">
                                {{ $retention->supplier->full_name }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-mono text-dark-300">
                                {{ $retention->code }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-mono text-dark-300">
                                {{ $retention->supplier->ci }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-dark-300">
                                {{ $retention->summary }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-right font-mono text-dark-300">
                                {{ number_format((float) $retention->amount, 2, ',', '.') }}
                            </td>
                            @foreach ($retention->discounts as $discount)
                                <td class="whitespace-nowrap px-4 py-3.5 text-sm text-right font-mono text-dark-300">
                                    {{ number_format((float) $discount->amount, 2, ',', '.') }}
                                </td>
                            @endforeach
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-right font-mono font-semibold text-primary-300">
                                {{ number_format((float) $retention->calculate_total, 2, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-center text-dark-300">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('retention.pdf.form', $retention->id) }}" target="_blank"
                                       class="p-1.5 rounded-lg text-dark-400 hover:text-red-400 hover:bg-red-600/10 transition-all duration-200"
                                       title="Descargar PDF">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('retention.form', $retention->id) }}"
                                       class="p-1.5 rounded-lg text-dark-400 hover:text-blue-400 hover:bg-blue-600/10 transition-all duration-200"
                                       title="Editar">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete('{{ $retention->id }}')"
                                            class="p-1.5 rounded-lg text-dark-400 hover:text-red-400 hover:bg-red-600/10 transition-all duration-200 cursor-pointer"
                                            title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $taxes->count() + 8 }}" class="px-4 py-16">
                                <div class="flex flex-col items-center justify-center gap-4 text-center">
                                    <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-dark-600/30 border border-dark-500/20">
                                        <svg class="w-8 h-8 text-dark-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-dark-300">Sin retenciones</p>
                                        <p class="text-sm text-dark-400 mt-1">No hay retenciones registradas para este período.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
