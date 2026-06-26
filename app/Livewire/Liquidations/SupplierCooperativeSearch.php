<?php

namespace App\Livewire\Liquidations;

use App\Models\Supplier;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class SupplierCooperativeSearch extends Component
{
    #[Modelable]
    public $ci;

    public $searchTerm = '';

    public $filteredSuppliers = [];

    public $showList = false;

    public function mount($ci = null)
    {
        $this->ci = $ci;
        $this->searchTerm = $ci;
    }

    public function render()
    {
        return view('livewire.liquidations.supplier-cooperative-search');
    }

    public function updatedSearchTerm()
    {
        $search = trim($this->searchTerm);
        if (strlen($search) < 2) {
            $this->filteredSuppliers = [];

            return;
        }

        $this->filteredSuppliers = Supplier::query()
            ->with(['person', 'cooperative'])
            ->whereHas('person', function ($query) use ($search) {
                $query->where('ci', 'LIKE', "%{$search}%")
                    ->orWhere('full_name', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('cooperative', function ($query) use ($search) {
                $query->where('NIT', 'LIKE', "%{$search}%")
                    ->orWhere('NIM', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();
    }

    public function selectSupplier($id)
    {
        $supplier = Supplier::with(['person', 'cooperative'])->find($id);
        if (! $supplier) {
            return;
        }

        $this->ci = $supplier->ci;
        $this->searchTerm = $supplier->ci;
        $this->showList = false;

        $this->dispatch('supplier-selected', [
            'id' => $supplier->id,
            'full_name' => $supplier->full_name,
            'phone' => $supplier->phone,
            'ci' => $supplier->ci,
            'NIM' => $supplier->cooperative?->NIM,
            'NIT' => $supplier->cooperative?->NIT,
            'concession' => $supplier->cooperative?->concession,
            'mine' => $supplier->cooperative?->mine,
            'municipality' => $supplier->cooperative?->municipality,
            'name' => $supplier->cooperative?->name,
        ]);
    }

    public function showDropdown()
    {
        $this->showList = true;
    }

    public function hideDropdown()
    {
        usleep(200000);
        $this->showList = false;
        $this->ci = $this->searchTerm;
        $this->dispatch('supplier-ci-manual', ['ci' => $this->ci]);
    }
}
