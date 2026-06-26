<div class="space-y-4">

    @can('Crear usuarios')
        
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
        <x-button icon="clipboard-document-list" x-on:click="$tsui.open.modal('modal-id')" class="w-full sm:w-auto">
            Agregar Usuario
        </x-button>
    </div>
    @endcan
    <div>
        <x-table :$headers :$rows filter paginate loading id="users">
            @interact('column_action', $row)
                <div class="flex gap-1 flex-row justify-center">
                    @can('Editar usuarios')
                        
                    <x-button.circle icon="pencil" color="blue" light
                    wire:click="$dispatch('load::user', { 'user' : '{{ $row->id }}'})" />
                    @endcan
                    @can('Eliminar usuarios')
                        
                    <x-button.circle icon="trash" color="red" light onclick="confirmDelete('{{ $row->id }}')" />
                        @endcan
                </div>
            @endinteract
        </x-table>
    </div>
    @include('livewire.users.form')
</div>
