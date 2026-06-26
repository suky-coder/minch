<div>
    <x-card>
        <x-slot:header>
            <span class="font-semibold text-base">{{ $this->id ? 'Actualizar' : 'Agregar' }} | Recibo</span>
        </x-slot:header>
        <div class="row ">
            <form id="user-create" wire:submit="save" class="space-y-2">
                <div class="space-y-1">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-1/2">
                            <x-input label="Nº LOTE" type="number" placeholder="NIT" wire:model="lote" />
                        </div>
                        <div class="w-full sm:w-1/2">
                            <x-input label="FECHA DE LIQ." type="text" placeholder="NIM" wire:model="date" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">

                        <div class="w-full sm:w-1/2">
                            <x-input label="PROVEEDOR." type="text" placeholder="NIM" wire:model="full_name" />
                        </div>
                        <div class="w-full sm:w-1/2">
                            <livewire:liquidations.supplier-cooperative-search :ci="$ci" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">

                        <div class="w-full sm:w-1/2">
                            <x-input label="COOP. MiNERA:" type="text" placeholder="NIM" wire:model="name" />
                        </div>

                        <div class="w-full sm:w-1/2">
                            <x-input label="NIM:" type="text" placeholder="NIM" wire:model="nim" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">

                        <div class="w-full sm:w-1/2">
                            <x-input label="CONCESIÓN:" type="text" placeholder="NIM" wire:model="concession" />
                        </div>
                        <div class="w-full sm:w-1/2">
                            <x-input label="LAB. QUÍMICO:" type="text" placeholder="NIM" wire:model="lab_quimico" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">

                        <div class="w-full sm:w-1/2">
                            <x-input label="MINA:" type="text" placeholder="NIM" wire:model="mine" />
                        </div>
                        <div class="w-full sm:w-1/2">
                            <x-input label="NÚMERO LAB.:" type="text" placeholder="NIM" wire:model="number_lab" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">

                        <div class="w-full sm:w-1/2">
                            <x-input label="MUNICIPIO:" type="text" placeholder="NIM" wire:model="municipality" />
                        </div>
                        <div class="w-full sm:w-1/2">
                            <x-input label="CÓDIGO:" type="text" placeholder="NIM" wire:model="codigo" />
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-1/2">
                            <h1>Cotizacióm Quincenal</h1>
                        </div>
                        <div class="w-full sm:w-1/2">
                            <h1>Cotizaciones</h1>
                        </div>
                    </div>
                    <div x-data="{
                        tmh: 0,
                        h2o: 0,
                        merma: 0,
                        dm: 0,
                        zinc: 0,
                        Zn: 0,
                        base: 0,
                        maquila: 0,
                        basePorcentaje: 0,
                        baseEscalacion: 0,
                        Ag: 0,
                        Sb:0,
                        Fe:0,
                        SiO2:0,
                        Sn:0,
                        As:0,
                        PAs:0,
                        PAsUSD:0,
                        PAsp:0,
                        PFe:0,
                        PFeUSD:0,
                        PFep:0,
                        get tms() {
                            return this.tmh - (this.tmh * this.h2o / 100);
                        },
                        get tmns() {
                            return -(this.tms * this.merma / 100) + this.tms;
                        },
                        get ag() {
                            return parseFloat(this.dm) * 100;
                        },
                        get platacalculate() {
                            return (parseFloat(this.dm) * 100) / 31.1035;
                        },
                        get zincporcentual() {
                            return this.zinc - 8;
                        },
                        get plataporcentual() {
                            return ((parseFloat(this.dm) * 100) / 31.1035) - 3;
                        },
                        get totalzinc() {
                            return (parseFloat(this.zinc) - 8) * this.Zn / 100;
                        },
                        get platavalue() {
                            return this.Ag * 0.7;
                        },
                        get totalplata() {
                            return (((parseFloat(this.dm) * 100) / 31.1035) - 3) * (this.Ag * 0.7);
                        },
                        get baseEscala() {
                            if (this.Zn > this.base) {
                                return this.Zn - this.base;
                            } else {
                                return 0;
                            }
                        },
                        get baseTotal() {
                            if (this.Zn > this.base) {
                                return (this.Zn - this.base) * this.basePorcentaje;
                            } else {
                                return 0 * this.basePorcentaje;
                            }
                        },
                        get AsTotal(){
                            if(this.As > this.PAs){
                                return ((this.As-this.PAs)*(this.PAsUSD*this.PAsp))*100000;
                            }
                            else{
                                return 0;
                            }
                        },
                        get FeTotal(){
                            if(this.Fe > this.PFe){
                                return ((this.Fe-this.PFe)*(this.PFeUSD*this.PFep));
                            }
                            else{
                                return 0;
                            }
                        }
                    
                    
                    
                    }">

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2">
                                <x-input prefix="Zn:" type="text" wire:model="NIM" suffix="USD/TM" />
                            </div>
                            <div class="w-full sm:w-1/2">
                                <x-input prefix="Zn:" type="text" wire:model="Zn" x-model="Zn" suffix="USD/TM" />
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/2">
                                <x-input prefix="Ag:" type="text" wire:model="NIM" suffix="USD/OZ" />
                            </div>
                            <div class="w-full sm:w-1/2">
                                <x-input prefix="Ag:" type="text" wire:model="NIM" x-model="Ag"
                                    suffix="USD/OZ" />
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/3">
                                <h1>Pesos</h1>
                            </div>
                            <div class="w-full sm:w-1/3">
                                <h1>Leyes</h1>
                            </div>
                            <div class="w-full sm:w-1/3">
                                <h1>Contenidos</h1>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="TMH:" x-model="tmh" type="number" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Zn" suffix="%" x-model="zinc" type="number" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="As" suffix="%" x-model="As"/>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="H2O:" suffix="%" x-model="h2o" type="number" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Ag" x-bind:value="ag.toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Sb" suffix="%" x-model="Sb"/>
                            </div>
                        </div>

                        {{-- TMS: calculado automáticamente --}}
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="TMS:" :value="''" x-bind:value="tms.toFixed(3)" readonly />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input suffix="Dm" x-model="dm" type="number" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Fe" suffix="%" x-model="Fe" />
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="MERMA:" suffix="%" x-model="merma" type="number" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Maquila:" wire:model="maquila" x-model="maquila" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="SiO2" suffix="%" x-model="SiO2"/>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="TMNS:" x-bind:value="tmns.toFixed(3)" readonly />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Base:" wire:model="base" x-model="base" />
                            </div>
                            <div class="w-full sm:w-1/3">
                                <x-input prefix="Sn" suffix="%" />
                            </div>
                        </div>


                        <div class="w-full sm:w-1/3">
                            <h1>Deducciones</h1>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/4">
                                <x-input prefix="Zinz:" x-bind:value="parseFloat(zinc)" readonly />
                            </div>
                            <div class="w-full sm:w-1/4">
                                <x-input suffix="%" :value="''" x-bind:value="zincporcentual.toFixed(2)"
                                    readonly />
                            </div>
                            <div class="w-full sm:w-1/4">
                                <x-input x-bind:value="parseFloat(Zn)" readonly />
                            </div>
                            <div class="w-full sm:w-1/4">
                                <x-input prefix="=" value="''" x-bind:value="totalzinc.toFixed(2)" readonly />
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/4">
                                <x-input prefix="Plata:" x-bind:value="platacalculate.toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/4">
                                <x-input suffix="Oz/Tm" :value="''" x-bind:value="plataporcentual.toFixed(2)"
                                    readonly />
                            </div>
                            <div class="w-full sm:w-1/4">
                                <x-input suffix="%" :value="''" x-bind:value="platavalue.toFixed(2)"
                                    readonly />
                            </div>
                            <div class="w-full sm:w-1/4">
                                <x-input prefix="=" :value="''" x-bind:value="totalplata.toFixed(2)"
                                    readonly />
                            </div>
                        </div>
                        <div class="w-full">
                            <h1>Gastos de tratamientos</h1>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full">
                                <x-input prefix="Maquila:" x-bind:value="parseFloat(maquila).toFixed(2)" readonly />
                            </div>

                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="Base/Escalacion:" x-bind:value="parseFloat(Zn).toFixed(2)"
                                    readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-bind:value="parseFloat(base).toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-bind:value="baseEscala.toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="basePorcentaje" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="=" :value="''" x-bind:value="baseTotal.toFixed(2)"
                                    readonly />
                            </div>
                        </div>
                        <div class="w-full">
                            <h1>Penalidades</h1>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="As:" :value="''" x-bind:value="parseFloat(As).toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PAs"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="USD:" x-model="PAsUSD" suffix="/TMS" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PAsp"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="="  :value="''" x-bind:value="AsTotal.toFixed(2)" readonly />
                            </div>
                        </div>
                         <div class="flex flex-col sm:flex-row gap-2">
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="Sb:" :value="''" x-bind:value="parseFloat(Sb).toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PSb"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="USD:" x-model="PSbUSD" suffix="/TMS" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PSbp"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="=" :value="''" x-bind:value="baseTotal.toFixed(2)" readonly />
                            </div>
                        </div>
                         <div class="flex flex-col sm:flex-row gap-2">
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="Fe:" :value="''" x-bind:value="parseFloat(Fe).toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PFe"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="USD:" x-model="PFeUSD" suffix="/TMS" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PFep"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="=" :value="''" x-bind:value="FeTotal.toFixed(2)" readonly />
                            </div>
                        </div>
                         <div class="flex flex-col sm:flex-row gap-2">
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="SiO2:" :value="''" x-bind:value="parseFloat(SiO2).toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PSiO2"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="USD:" x-model="PSiO2USD" suffix="/TMS" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PSiO2p"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="=" :value="''" x-bind:value="baseTotal.toFixed(2)" readonly />
                            </div>
                        </div>
                         <div class="flex flex-col sm:flex-row gap-2">
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="Sn:" :value="''" x-bind:value="parseFloat(Sn).toFixed(2)" readonly />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PSn"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="USD:" x-model="PSnUSD" suffix="/TMS" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input :value="''" x-model="PSnp"  suffix="%" />
                            </div>
                            <div class="w-full sm:w-1/5">
                                <x-input prefix="=" :value="''" x-bind:value="baseTotal.toFixed(2)" readonly />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button wire:navigate href="{{ route('retentions') }}" text="Cancelar" icon="x-mark"
                    color="secondary" />
                @if ($this->id)
                    <x-button wire:click.prevent="update()" wire:loading.attr="disabled" text="Actualizar"
                        icon="clipboard-document" color="fuchsia" outline />
                @else
                    <x-button wire:click.prevent="store()" wire:loading.attr="disabled" text="Guardar"
                        icon="archive-box-arrow-down" color="fuchsia" outline />
                @endif
            </div>
        </x-slot:footer>
    </x-card>
</div>
