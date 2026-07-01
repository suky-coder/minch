<div x-on:close-modal.window="$tsui.close.modal('modal-id')">
    <x-modal title="{{ $this->customerId ? 'Actualizar' : 'Crear' }} | Cliente" id="modal-id" persistent>
        <form id="customer-form" wire:submit="save" class="space-y-2">
            <div class="w-full">
                <x-input label="Nombre Completo" placeholder="Nombre completo" wire:model="full_name" />
            </div>
            <div class="w-full">
                <x-input label="Cédula de Identidad" placeholder="C.I." wire:model="ci" />
            </div>
            <div class="w-full">
                <x-upload label="Archivo (opcional)" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" wire:model="file" tip="Formatos: PDF, DOC, DOCX, JPG, PNG. Máx 5MB" />
                @if ($this->existingFile)
                    <div class="mt-1 text-sm text-gray-500">
                        Archivo actual:
                        <a href="{{ \Storage::url($this->existingFile) }}" target="_blank" class="text-primary-600 hover:text-primary-800 underline">Ver archivo</a>
                    </div>
                @endif
            </div>
            <div class="w-full">
                <x-select.styled label="Cooperativa (opcional)" wire:model="cooperative_id" :options="\App\Models\Cooperative::select('id as value', 'name as label')->get()->toArray()" />
            </div>
        </form>
        <x-slot:footer>
            <x-button color="outline" type="button" wire:click="clear()">
                Cancelar
            </x-button>
            @if ($this->customerId)
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
