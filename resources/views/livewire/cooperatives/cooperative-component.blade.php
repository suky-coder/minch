<div class="space-y-4">

    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
        <x-button icon="clipboard-document-list" x-on:click="$tsui.open.modal('modal-id')" class="w-full sm:w-auto">
            Agregar Cooperativa
        </x-button>
    </div>

    <div>
        <x-table :$headers :$rows filter paginate loading id="suppliers">
            @interact('column_action', $row)
                <div class="flex gap-1">
                    <x-button.circle icon="pencil" color="blue" light
                        wire:click="$dispatch('load::cooperative', { 'cooperative' : '{{ $row->id }}'})" />
                    <x-button.circle icon="trash" color="red" light onclick="confirmDelete('{{ $row->id }}')" />
                </div>
            @endinteract
        </x-table>
    </div>
    @include('livewire.cooperatives.form')

</div>
