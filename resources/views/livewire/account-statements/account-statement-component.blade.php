<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-700 p-1">
            <button type="button"
                wire:click="$set('activeTab', 'supplier')"
                class="px-4 py-2 text-sm font-medium rounded-md transition {{ $this->activeTab === 'supplier' ? 'bg-primary-500 text-white' : 'text-gray-600 dark:text-gray-300' }}">
                Proveedores
            </button>
            <button type="button"
                wire:click="$set('this->activeTab', 'customer')"
                class="px-4 py-2 text-sm font-medium rounded-md transition {{ $this->activeTab === 'customer' ? 'bg-primary-500 text-white' : 'text-gray-600 dark:text-gray-300' }}">
                Clientes
            </button>
        </div>

        @can('Crear estados de cuenta')
            <x-button icon="clipboard-document-list" x-on:click="$tsui.open.modal('modal-id')" class="w-full sm:w-auto">
                Agregar {{ $holderLabel }}
            </x-button>
        @endcan
    </div>

    <div>
        <x-table :$headers :$rows filter paginate loading id="account-statements">
            @interact('column_action', $row)
                <div class="flex gap-1 flex-row justify-center">
                    @if ($row->file)
                        @php
                            $filePath = $this->activeTab === 'customer'
                                ? 'customers/contract/' . $row->file
                                : 'suppliers/contract/' . $row->file;
                        @endphp
                        <x-button.circle icon="document" color="red" outline
                            href="{{ Storage::url($filePath) }}" target="_blank" />
                    @endif
                    @can('Ver estados de cuenta')
                        <x-button.circle icon="eye" color="violet" light
                            href="{{ route('accounts.statement.view', ['type' => $this->activeTab, 'id' => $row->id]) }}"
                            wire:navigate />
                    @endcan
                    @can('Editar estados de cuenta')
                        @if ($this->activeTab === 'supplier')
                            <x-button.circle icon="pencil" color="blue" light
                                wire:click="$dispatch('load::supplier', { supplier: {{ $row->id }} })" />
                        @else
                            <x-button.circle icon="pencil" color="blue" light
                                wire:click="$dispatch('load::customer', { customer: {{ $row->id }} })" />
                        @endif
                    @endcan
                    @can('Eliminar estados de cuenta')
                        <x-button.circle icon="trash" color="red" light
                            wire:click="delete{{ ucfirst($this->activeTab) }}({{ $row->id }})"
                            wire:confirm="¿Está seguro de eliminar este registro?" />
                    @endcan
                </div>
            @endinteract
        </x-table>
    </div>

    @include('livewire.account-statements.form')
</div>
