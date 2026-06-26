{{-- resources/views/livewire/transactions/transaction-component.blade.php --}}
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-end">

        <div class="flex flex-wrap gap-2">
            <x-date month-year-only wire:model="selectedDate" />
        </div>

        <div>
            <x-button color="sky" icon="magnifying-glass-circle" class="w-full sm:w-auto" outline
                wire:click.prevent="$refresh">Consultar</x-button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach ($accounts as $account)
            <x-card header="Nro. {{ $account->account_number }}">
                <div>
                    <x-icon name="building-library" class="h-5 w-5">
                        <x-slot:right>
                            <span style="color:{{ $account->color }}">
                                {{ $account->name }}</span>
                        </x-slot:right>
                    </x-icon>
                    <br>
                    <x-icon name="plus-circle" class="h-5 w-5">
                        <x-slot:right>
                            <span>Debe: {{ $account->amountD ?? 0 }}</span>
                        </x-slot:right>
                    </x-icon>
                    <br>
                    <x-icon name="minus-circle" class="h-5 w-5">
                        <x-slot:right>
                            <span>Haber: {{ $account->amountC ?? 0 }}</span>
                        </x-slot:right>
                    </x-icon><br>
                    <x-icon name="calculator" class="h-5 w-5">
                        <x-slot:right>
                            <span
                                class="rounded-md outline-hidden inline-flex items-center border px-2 py-0.5 font-bold text-xs border-neutral-300 bg-neutral-300 dark:bg-neutral-700/30 dark:border-transparent text-neutral-600 dark:text-neutral-400"
                                style="background:{{ $account->amountS<=0?'rgb(103, 3, 3)':'rgb(2, 110, 15)' }} ; color: white;">
                               Saldo: {{ number_format($account->amountS,2,',','.') }}
                            </span>
                        </x-slot:right>
                    </x-icon><br>
                </div>
                <x-slot:footer>
                    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                        <x-button.circle icon="eye" color="indigo" light
                            href="{{ route('transactions.view', [$account->id, $this->selectedDate, 'supplier' => $this->selectedSupplierId]) }}" />
                    </div>
                </x-slot:footer>
            </x-card>
        @endforeach
    </div>

    {{ $accounts->links('ts-ui::components.table.paginators') }}
</div>