<?php

namespace App\Livewire\Liquidations;

use Livewire\Component;

class LiquidationForm extends Component
{
    public $id = 0, $ci, $supplier_id;
    public
        $full_name,
        $nim, $nit, $concession, $mine, $municipality, $name;
    public function render()
    {
        return view('livewire.liquidations.liquidation-form');
    }
    protected function getListeners()
    {
        return [
            'supplier-selected' => 'onSupplierSelected',
            'supplier-ci-manual' => 'onSupplierCiManual',
        ];
    }
    public function onSupplierCiManual($payload)
    {
        $this->ci = $payload['ci'];
        $this->supplier_id = null;
    }
    public function onSupplierSelected($payload)
    {
        $this->ci = $payload['ci'];
        $this->full_name = $payload['full_name'];
        $this->supplier_id = $payload['id'];
        $this->nim = $payload['NIM'];
        $this->nit = $payload['NIT'];
        $this->concession = $payload['concession'];
        $this->mine = $payload['mine'];
        $this->municipality = $payload['municipality'];
        $this->name = $payload['name'];
    }
}
