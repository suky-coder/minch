<div class="space-y-6">

    {{-- Barra superior: volver + info cuenta + acciones --}}
    <div class="bg-dark-800/40 backdrop-blur-sm rounded-xl border border-dark-600/20 p-4 space-y-4">

        {{-- Fila 1: Volver + título --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('transactions') }}" wire:navigate
                   class="inline-flex items-center gap-2 text-sm font-medium text-dark-300 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Volver
                </a>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @can('Crear movimientos de libro de bancos')
                    <a href="{{ route('transactions.form', [$this->date, $this->idAccount]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-500 transition-all duration-200 shadow-lg shadow-primary-600/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Agregar movimiento
                    </a>
                @endcan
            </div>
        </div>

        {{-- Fila 2: Datos de la cuenta --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-dark-700/40 border border-dark-600/20">
                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-600/10 border border-primary-500/20">
                    <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 0 4.5 6h.75m13.5 0h.75a.75.75 0 0 0 .75-.75V4.5M4.5 18.75h15m-15 0V8.25a2.25 2.25 0 0 1 2.25-2.25h10.5A2.25 2.25 0 0 1 19.5 8.25v10.5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-medium text-dark-400 uppercase tracking-wider">Cuenta</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->account }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-dark-700/40 border border-dark-600/20">
                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-600/10 border border-emerald-500/20">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-medium text-dark-400 uppercase tracking-wider">Moneda</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->moneda }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-dark-700/40 border border-dark-600/20">
                <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-purple-600/10 border border-purple-500/20">
                    <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-medium text-dark-400 uppercase tracking-wider">Periodo</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->dateT }}</p>
                </div>
            </div>
        </div>

        {{-- Fila 3: Botones de exportación --}}
        <div class="flex flex-wrap gap-3 justify-end">
            @can('PDF libro de bancos')
                <a href="{{ route('transaction.account.pdf', [$this->start, $this->end, $this->idAccount]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-red-300 bg-red-600/10 border border-red-500/20 hover:bg-red-600/20 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    PDF
                </a>
            @endcan
            @can('Excel libro de bancos')
                <a href="{{ route('transaction.account.excel', [$this->start, $this->end, $this->idAccount]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-emerald-300 bg-emerald-600/10 border border-emerald-500/20 hover:bg-emerald-600/20 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Excel
                </a>
            @endcan
        </div>
    </div>

    {{-- Tabla de movimientos --}}
    <div class="rounded-xl bg-dark-800/40 backdrop-blur-sm border border-dark-600/20 overflow-hidden">

        <div class="relative soft-scrollbar overflow-x-auto">
            <x-ui.loading-spinner class="text-primary-500 dark:text-dark-300 absolute bottom-0 left-0 right-0 top-0 m-auto h-10 w-10" wire:loading="quantity,search" />

            <table class="min-w-full divide-y divide-dark-600/20"
                   wire:loading.class="cursor-not-allowed select-none opacity-25">

                {{-- Cabecera info --}}
                <thead>
                    <tr class="bg-dark-700/60">
                        <th scope="col" colspan="4"
                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            <span class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span>
                                CUENTA: {{ $this->account }}
                            </span>
                        </th>
                        <th scope="col" colspan="4"
                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">
                            <span class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                MONEDA: {{ $this->moneda }}
                            </span>
                        </th>
                    </tr>
                    <tr class="bg-dark-700/30">
                        <th scope="col" colspan="8"
                            class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-dark-400">
                            <span class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                                PERIODO: {{ $this->dateT }}
                            </span>
                        </th>
                    </tr>

                    {{-- Cabecera columnas --}}
                    <tr class="bg-dark-700/50">
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">Nº</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">Fecha</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">Descripción</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-dark-300">Dcto. Ref.</th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-emerald-400">Debe</th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-red-400">Haber</th>
                        <th scope="col" class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-primary-400">Saldo</th>
                        <th scope="col" class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-dark-300">Opciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-dark-600/10">
                    @forelse ($transactions as $transaction)
                        <tr class="group transition-all duration-200 hover:bg-primary-500/5"
                            wire:key="8b700ca168f875b5e2a3c9329ba84d55"
                            style="animation: fade-in-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; animation-delay: {{ $loop->index * 0.03 }}s;">
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-mono text-dark-300">
                                {{ $transaction->transaction->formatted_last_number }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-dark-300">
                                {{ $transaction->date }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-dark-200 max-w-[200px] truncate" title="{{ $transaction->description }}">
                                {{ $transaction->description }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-dark-300 font-mono">
                                {{ $transaction->transaction->number_label }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-right font-mono font-medium text-emerald-300">
                                {{ $transaction->type == 'D' || $transaction->type == 'B' ? number_format($transaction->amount, 2, '.', ',') : '' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-right font-mono font-medium text-red-300">
                                {{ $transaction->type == 'C' ? number_format($transaction->amount, 2, '.', ',') : '' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-right font-mono font-semibold text-primary-300">
                                {{ number_format($transaction->balance, 2, '.', ',') }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-center text-dark-300">
                                @if ($transaction->type != 'B')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('receipt.transaction.pdf', $transaction->id) }}"
                                           class="p-1.5 rounded-lg text-dark-400 hover:text-red-400 hover:bg-red-600/10 transition-all duration-200"
                                           title="Descargar PDF">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                            </svg>
                                        </a>
                                        @can('Editar movimientos de libro de bancos')
                                            <a href="{{ route('transactions.form', [$this->date, $this->idAccount, $transaction->id]) }}"
                                               class="p-1.5 rounded-lg text-dark-400 hover:text-blue-400 hover:bg-blue-600/10 transition-all duration-200"
                                               title="Editar">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('Eliminar movimientos de libro de bancos')
                                            <button onclick="confirmDelete('{{ $transaction->id }}')"
                                                    class="p-1.5 rounded-lg text-dark-400 hover:text-red-400 hover:bg-red-600/10 transition-all duration-200 cursor-pointer"
                                                    title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16">
                                <div class="flex flex-col items-center justify-center gap-4 text-center">
                                    <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-dark-600/30 border border-dark-500/20">
                                        <svg class="w-8 h-8 text-dark-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-dark-300">Sin movimientos</p>
                                        <p class="text-sm text-dark-400 mt-1">No hay movimientos registrados para este período.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4 flex justify-center">
        <nav role="navigation" aria-label="Pagination Navigation">
            {{ $transactions->links() }}
        </nav>
    </div>
</div>
