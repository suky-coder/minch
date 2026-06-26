<div class="space-y-4">

    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
        <x-button icon="clipboard-document-list" x-on:click="$tsui.open.modal('modal-id')" class="w-full sm:w-auto">
            Agregar Role
        </x-button>
    </div>
    <div>
        <x-table :$headers :$rows filter paginate loading id="roles">
            @interact('column_action', $row)
                <div class="flex gap-1 flex-row justify-center">
                    <x-button.circle icon="pencil" color="blue" light
                        wire:click="$dispatch('load::role', { 'role' : '{{ $row->id }}'})" />
                    <x-button.circle icon="trash" color="red" light onclick="confirmDelete('{{ $row->id }}')" />
                </div>
            @endinteract
        </x-table>
    </div>
    @include('livewire.roles.form')
</div>
