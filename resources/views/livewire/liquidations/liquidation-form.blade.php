<div>
    <x-card>
        <x-slot:header>
            <span class="font-semibold text-base">{{ $this->id ? 'Actualizar' : 'Agregar' }}
                <span x-text="metal === 'zn' ? 'Zn (Zinc)' : 'Pb (Plomo)'"></span>
                | Recibo
            </span>
        </x-slot:header>
        <div class="row ">
            <form id="user-create" wire:submit="save" class="space-y-2">
                <div class="space-y-1">

                    <div x-data="{
                        // Metal selector
                        metal: @entangle('metal'),
                        tab: 'generales',
                        // Shared defaults
                        tmh: 22.720,
                        h2o: 11.45,
                        merma: 1,
                        dm: 6.91,
                        base: 3000,
                        basePorcentaje: 0.15,
                        baseEscalacion: 0,
                        maquila: 90,
                        Ag: 0,
                        Sb:0.1,
                        Fe:9.5,
                        SiO2:5,
                        Sn:0,
                        As:0.1,
                        PAs:0.5,
                        PAsUSD:3,
                        PAsp:0.1,
                        PFe:8,
                        PFeUSD:3,
                        PFep:1,
                        PSb:0.5,
                        PSbUSD:3,
                        PSbp:0.1,
                        PSiO2:3,
                        PSiO2USD:3,
                        PSiO2p:1,
                        PSn:0.5,
                        PSnUSD:3,
                        PSnp:0.1,
                        AgUSD: 75,
                        agNIM:76.38,
                        regaliaAg:3.6,
                        factorRegalia:6.96,
                        tc:8.70,
                        flete:160,
                        rollback:42,
                        remesaPct:0.8,
                        cns:1.8,
                        comibol:1,
                        fedecomin:1,
                        fencomin:0.4,
                        aporteCoop:0,
                        // Zn-specific
                        NIM: 1.55,
                        zinc: 54.06,
                        Zn: 3434,
                        regaliaZn:3,
                        // Pb-specific
                        nimPb: 0.88,
                        plomo: 52.88,
                        Pb: 1936,
                        regaliaPb:3,
                        refinacion: 0.05,
                        payabilidadPb: 0.95,
                        payabilidadAg: 0.95,
                        init() {
                            this.setDefaults();
                        },
                        setDefaults() {
                            if (this.metal === 'zn') {
                                this.tmh = 22.720;
                                this.h2o = 11.45;
                                this.dm = 6.91;
                                this.base = 3000;
                                this.maquila = 90;
                                this.Sb = 0.1;
                                this.Fe = 9.5;
                                this.SiO2 = 5;
                                this.Sn = 0;
                                this.As = 0.1;
                                this.PAsUSD = 3;
                                this.PSbUSD = 3;
                                this.PFeUSD = 3;
                                this.PSiO2USD = 3;
                                this.PSnUSD = 3;
                                this.AgUSD = 75;
                                this.tc = 8.70;
                                this.flete = 160;
                                this.rollback = 42;
                                this.comibol = 1;
                                this.NIM = 1.55;
                                this.zinc = 54.06;
                                this.Zn = 3434;
                                this.regaliaZn = 3;
                            } else {
                                this.tmh = 35.920;
                                this.h2o = 10.67;
                                this.dm = 24.21;
                                this.base = 2000;
                                this.maquila = 0;
                                this.Sb = 1.30;
                                this.Fe = 6.20;
                                this.SiO2 = 3;
                                this.Sn = 0;
                                this.As = 1.30;
                                this.PAsUSD = 3.5;
                                this.PSbUSD = 3.5;
                                this.PFeUSD = 3.5;
                                this.PSiO2USD = 3.5;
                                this.PSnUSD = 3.5;
                                this.AgUSD = 73.50;
                                this.tc = 9.10;
                                this.flete = 180;
                                this.rollback = 38;
                                this.comibol = 0;
                                this.nimPb = 0.88;
                                this.plomo = 52.88;
                                this.Pb = 1936;
                                this.regaliaPb = 3;
                            }
                        },
                        get tms() {
                            return Number((this.tmh - (this.tmh * this.h2o / 100)).toFixed(3));
                        },
                        get tmns() {
                            return Number((-(this.tms * this.merma / 100) + this.tms).toFixed(3));
                        },
                        get ag() {
                            return parseFloat(this.dm) * 100;
                        },
                        get platacalculate() {
                            return (parseFloat(this.dm) * 100) / 31.1035;
                        },
                        get ley() {
                            return this.metal === 'zn' ? parseFloat(this.zinc) : parseFloat(this.plomo);
                        },
                        get precioMetal() {
                            return this.metal === 'zn' ? Number(this.Zn) : Number(this.Pb);
                        },
                        get nim() {
                            return this.metal === 'zn' ? Number(this.NIM) : Number(this.nimPb);
                        },
                        get deduccionMetal() {
                            return this.metal === 'zn' ? 8 : 3;
                        },
                        get deduccionAg() {
                            return this.metal === 'zn' ? 3 : 1.6;
                        },
                        get payAg() {
                            return this.metal === 'zn' ? 0.7 : Number(this.payabilidadAg);
                        },
                        get leyPorcentual() {
                            return this.ley - this.deduccionMetal;
                        },
                        get valorMetal() {
                            if (this.metal === 'zn') {
                                return this.leyPorcentual * this.precioMetal / 100;
                            } else {
                                return this.leyPorcentual * this.precioMetal * Number(this.payabilidadPb) / 100;
                            }
                        },
                        get plataporcentual() {
                            return this.platacalculate - this.deduccionAg;
                        },
                        get platavalue() {
                            return Number(this.AgUSD) * this.payAg;
                        },
                        get totalplata() {
                            return this.plataporcentual * this.platavalue;
                        },
                        get baseEscala() {
                            if (this.precioMetal > Number(this.base)) {
                                return this.precioMetal - Number(this.base);
                            } else {
                                return 0;
                            }
                        },
                        get baseTotal() {
                            if (this.precioMetal > Number(this.base)) {
                                return (this.precioMetal - Number(this.base)) * Number(this.basePorcentaje);
                            } else {
                                return 0 * Number(this.basePorcentaje);
                            }
                        },
                        get refinacionTotal() {
                            return this.platacalculate * Number(this.refinacion);
                        },
                        get AsTotal(){
                            if(Number(this.As) > Number(this.PAs)){
                                return (Number(this.As)-Number(this.PAs))*(Number(this.PAsUSD)/Number(this.PAsp));
                            }
                            else{
                                return 0;
                            }
                        },
                        get SbTotal(){
                            if(Number(this.Sb) > Number(this.PSb)){
                                return (Number(this.Sb)-Number(this.PSb))*(Number(this.PSbUSD)/Number(this.PSbp));
                            }
                            else{
                                return 0;
                            }
                        },
                        get FeTotal(){
                            if(Number(this.Fe) > Number(this.PFe)){
                                return (Number(this.Fe)-Number(this.PFe))*(Number(this.PFeUSD)/Number(this.PFep));
                            }
                            else{
                                return 0;
                            }
                        },
                        get SiO2Total(){
                            if(Number(this.SiO2) > Number(this.PSiO2)){
                                return (Number(this.SiO2)-Number(this.PSiO2))*(Number(this.PSiO2USD)/Number(this.PSiO2p));
                            }
                            else{
                                return 0;
                            }
                        },
                        get SnTotal(){
                            if(Number(this.Sn) > Number(this.PSn)){
                                return (Number(this.Sn)-Number(this.PSn))*(Number(this.PSnUSD)/Number(this.PSnp));
                            }
                            else{
                                return 0;
                            }
                        },
                        get totalCT() {
                            const base = this.baseTotal + this.AsTotal + this.SbTotal + this.FeTotal + this.SiO2Total + this.SnTotal;
                            if (this.metal === 'zn') {
                                return parseFloat(this.maquila) + base;
                            } else {
                                return parseFloat(this.maquila) + this.refinacionTotal + base;
                            }
                        },
                        get valorNeto() {
                            return Number((this.valorMetal + this.totalplata - this.totalCT).toFixed(2));
                        },
                        get totalNetoUSD() {
                            return Number(this.valorNeto) * Number(this.tmns);
                        },
                        get gastosOp(){
                            return ((this.tmns*1000*this.ley/100*2.2046223*this.nim*5/100) + (Number(this.dm)*this.tmns/10*32.15073*Number(this.agNIM)*6/100)) -  ((this.tmns*1000*this.ley/100*2.2046223*this.nim*3/100) + (Number(this.dm)*this.tmns/10*32.15073*Number(this.agNIM)*3.6/100));
                        },
                        get totalFlete() {
                            return Number(this.flete) * Number(this.tmh);
                        },
                        get totalRollback() {
                            return Number(this.rollback) * Number(this.tmh);
                        },
                        get totalRemesa() {
                            return (this.totalNetoUSD * Number(this.remesaPct)/100) + 385;
                        },
                        get totalGastos() {
                            return this.totalFlete + this.totalRollback + this.gastosOp + this.totalRemesa;
                        },
                        get totalUSDperTMNS() {
                            return this.tmns ? (this.totalNetoUSD - this.totalGastos) / this.tmns : 0;
                        },
                        get totalUSD() {
                            return this.totalNetoUSD - this.totalGastos;
                        },
                        get totalBs() {
                            return this.totalUSD * Number(this.tc);
                        },
                        get regaliaMetalBs() {
                            const r = this.metal === 'zn' ? Number(this.regaliaZn) : Number(this.regaliaPb);
                            return (this.tmns*1000*this.ley/100*2.2046223*this.nim*r/100)*Number(this.factorRegalia);
                        },
                        get regaliaAgBs() {
                            return (Number(this.dm)*this.tmns/10*32.15073*Number(this.agNIM)*Number(this.regaliaAg)/100)*Number(this.factorRegalia)
                        },
                        get totalRM() {
                            return this.regaliaMetalBs + this.regaliaAgBs;
                        },
                        get cnsBs() {
                            return this.totalBs * Number(this.cns) / 100;
                        },
                        get comibolBs() {
                            return this.totalBs * Number(this.comibol) / 100;
                        },
                        get fedecominBs() {
                            return this.totalBs * Number(this.fedecomin) / 100;
                        },
                        get fencominBs() {
                            return this.totalBs * Number(this.fencomin) / 100;
                        },
                        get aporteCoopBs() {
                            return this.totalBs * Number(this.aporteCoop) / 100;
                        },
                        get totalAportes() {
                            return this.cnsBs + this.comibolBs + this.fedecominBs + this.fencominBs + this.aporteCoopBs;
                        },
                        get costoFinal() {
                            return this.totalBs - this.totalRM - this.totalAportes;
                        }
                    }">

                        {{-- Metal Selector --}}
                        <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 dark:bg-dark-700 rounded-lg">
                            <span class="text-sm font-semibold text-gray-600 dark:text-dark-300">Metal:</span>
                            <select x-model="metal" @change="setDefaults()"
                                class="rounded-lg border-gray-300 dark:border-dark-500 dark:bg-dark-600 text-sm font-medium focus:border-primary-500 focus:ring-primary-500">
                                <option value="zn">Zn (Zinc)</option>
                                <option value="pb">Pb (Plomo)</option>
                            </select>
                        </div>

                        {{-- Tab Navigation --}}
                        <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 dark:border-dark-600 pb-3">
                            <button @click="tab = 'generales'" type="button"
                                :class="tab === 'generales' ? 'bg-primary-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-dark-200 hover:bg-gray-200 dark:hover:bg-dark-500'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                                Datos Generales
                            </button>
                            <button @click="tab = 'pesos'" type="button"
                                :class="tab === 'pesos' ? 'bg-primary-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-dark-200 hover:bg-gray-200 dark:hover:bg-dark-500'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                                Pesos y Leyes
                            </button>
                            <button @click="tab = 'deducciones'" type="button"
                                :class="tab === 'deducciones' ? 'bg-primary-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-dark-200 hover:bg-gray-200 dark:hover:bg-dark-500'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                                Deducciones
                            </button>
                            <button @click="tab = 'gastos'" type="button"
                                :class="tab === 'gastos' ? 'bg-primary-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-dark-200 hover:bg-gray-200 dark:hover:bg-dark-500'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                                Gastos y Totales
                            </button>
                            <button @click="tab = 'regalias'" type="button"
                                :class="tab === 'regalias' ? 'bg-primary-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-dark-200 hover:bg-gray-200 dark:hover:bg-dark-500'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                                Regalías y Aportes
                            </button>
                        </div>

                        {{-- Tab 1: Datos Generales --}}
                        <div x-show="tab === 'generales'" x-transition>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/2">
                                    <x-input label="Nº LOTE" type="text" placeholder="MCH-###" wire:model="lote" />
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <x-input label="FECHA DE LIQ." type="text" placeholder="dd/mm/aaaa" wire:model="date" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/2">
                                    <x-input label="PROVEEDOR" type="text" placeholder="Nombre" wire:model="full_name" />
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <livewire:liquidations.supplier-cooperative-search :ci="$ci" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/2">
                                    <x-input label="COOP. MINERA:" type="text" wire:model="name" />
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <x-input label="NIM:" type="text" wire:model="nim" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/2">
                                    <x-input label="CONCESIÓN:" type="text" wire:model="concession" />
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <x-input label="LAB. QUÍMICO:" type="text" wire:model="lab_quimico" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/2">
                                    <x-input label="MINA:" type="text" wire:model="mine" />
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <x-input label="NÚMERO LAB.:" type="text" wire:model="number_lab" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/2">
                                    <x-input label="MUNICIPIO:" type="text" wire:model="municipality" />
                                </div>
                                <div class="w-full sm:w-1/2">
                                    <x-input label="CÓDIGO:" type="text" wire:model="codigo" />
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <div class="flex flex-col sm:flex-row gap-4 mb-2">
                                    <div class="w-full sm:w-1/2">
                                        <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300">Cotización Quincenal</h1>
                                    </div>
                                    <div class="w-full sm:w-1/2">
                                        <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300">Cotizaciones</h1>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full sm:w-1/2">
                                        {{-- Zn quincenal --}}
                                        <template x-if="metal === 'zn'">
                                            <x-input prefix="Zn:" type="text" wire:model="NIM" x-model="NIM" suffix="USD/TM" />
                                        </template>
                                        {{-- Pb quincenal --}}
                                        <template x-if="metal === 'pb'">
                                            <x-input prefix="Pb:" type="text" wire:model="nimPb" x-model="nimPb" suffix="USD/TM" />
                                        </template>
                                    </div>
                                    <div class="w-full sm:w-1/2">
                                        {{-- Zn mercado --}}
                                        <template x-if="metal === 'zn'">
                                            <x-input prefix="Zn:" type="text" wire:model="Zn" x-model="Zn" suffix="USD/TM" />
                                        </template>
                                        {{-- Pb mercado --}}
                                        <template x-if="metal === 'pb'">
                                            <x-input prefix="Pb:" type="text" wire:model="Pb" x-model="Pb" suffix="USD/TM" />
                                        </template>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full sm:w-1/2">
                                        <x-input prefix="Ag:" type="text" wire:model="agNIM" x-model="agNIM" suffix="USD/OZ" />
                                    </div>
                                    <div class="w-full sm:w-1/2">
                                        <x-input prefix="Ag:" type="text" wire:model="AgUSD" x-model="AgUSD" suffix="USD/OZ" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Pesos y Leyes --}}
                        <div x-show="tab === 'pesos'" x-transition>
                            <div class="flex flex-col sm:flex-row gap-4 mb-2">
                                <div class="w-full sm:w-1/3">
                                    <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300">Pesos</h1>
                                </div>
                                <div class="w-full sm:w-1/3">
                                    <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300">Leyes</h1>
                                </div>
                                <div class="w-full sm:w-1/3">
                                    <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300">Contenidos</h1>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="TMH:" x-model="tmh" type="number" />
                                </div>
                                <div class="w-full sm:w-1/3">
                                    {{-- Zn ley --}}
                                    <template x-if="metal === 'zn'">
                                        <x-input prefix="Zn" suffix="%" x-model="zinc" type="number" />
                                    </template>
                                    {{-- Pb ley --}}
                                    <template x-if="metal === 'pb'">
                                        <x-input prefix="Pb" suffix="%" x-model="plomo" type="number" />
                                    </template>
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

                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="TMS:" x-bind:value="tms.toFixed(3)" readonly />
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
                                    <x-input prefix="Sn" suffix="%" x-model="Sn" />
                                </div>
                            </div>

                        </div>

                        {{-- Tab 3: Deducciones --}}
                        <div x-show="tab === 'deducciones'" x-transition>
                            <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Deducciones</h1>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/4">
                                    <x-input x-bind:prefix="metal === 'zn' ? 'Zinc:' : 'Plomo:'" x-bind:value="ley" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input suffix="%" x-bind:value="leyPorcentual.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input x-bind:value="precioMetal" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" x-bind:value="valorMetal.toFixed(2)" readonly />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Plata:" x-bind:value="platacalculate.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input suffix="Oz/Tm" x-bind:value="plataporcentual.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input suffix="%" x-bind:value="platavalue.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" x-bind:value="totalplata.toFixed(2)" readonly />
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Gastos de Tratamiento</h1>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full sm:w-1/3">
                                        <x-input prefix="Maquila:" x-bind:value="parseFloat(maquila).toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 mt-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Base/Escalacion:" x-bind:value="precioMetal" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-bind:value="parseFloat(base).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-bind:value="baseEscala.toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="basePorcentaje" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="baseTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                                {{-- Refinacion (Pb only) --}}
                                <div x-show="metal === 'pb'" class="flex flex-col sm:flex-row gap-2 mt-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Refinacion:" x-model="refinacion" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="refinacionTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Penalidades</h1>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="As:" x-bind:value="parseFloat(As).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PAs" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PAsUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PAsp" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="AsTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Sb:" x-bind:value="parseFloat(Sb).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PSb" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PSbUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PSbp" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="SbTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Fe:" x-bind:value="parseFloat(Fe).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PFe" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PFeUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PFep" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="FeTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="SiO2:" x-bind:value="parseFloat(SiO2).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PSiO2" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PSiO2USD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PSiO2p" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="SiO2Total.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Sn:" x-bind:value="parseFloat(Sn).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PSn" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PSnUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input x-model="PSnp" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" x-bind:value="SnTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full">
                                        <x-input prefix="Total C/T:" x-bind:value="totalCT.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                                    <div class="w-full">
                                        <x-input prefix="Valor Neto:" x-bind:value="valorNeto.toFixed(2)" readonly suffix="USD/TMNS" />
                                    </div>
                                    <div class="w-full">
                                        <x-input prefix="TMNS:" x-bind:value="tmns.toFixed(3)" readonly />
                                    </div>
                                    <div class="w-full">
                                        <x-input prefix="Total Neto:" x-bind:value="totalNetoUSD.toFixed(2)" readonly suffix="USD" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 4: Gastos y Totales --}}
                        <div x-show="tab === 'gastos'" x-transition>
                            <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Gastos de Realización</h1>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Flete:" x-model="flete" suffix="USD/TMH" />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" x-bind:value="totalFlete.toFixed(2)" readonly suffix="USD" />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Roll Back:" x-model="rollback" suffix="USD/TMH" />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" x-bind:value="totalRollback.toFixed(2)" readonly suffix="USD" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Gastos Op.:" x-bind:value="gastosOp.toFixed(2)" readonly suffix="USD" />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Remesa:" x-model="remesaPct" suffix="%" />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" x-bind:value="totalRemesa.toFixed(2)" readonly suffix="USD" />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Total Gastos:" x-bind:value="totalGastos.toFixed(2)" readonly suffix="USD" />
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Resultados</h1>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full sm:w-1/3">
                                        <x-input prefix="Total USD:" x-bind:value="totalUSD.toFixed(2)" readonly suffix="USD" />
                                    </div>
                                    <div class="w-full sm:w-1/3">
                                        <x-input prefix="TC:" x-model="tc" suffix="Bs/USD" />
                                    </div>
                                    <div class="w-full sm:w-1/3">
                                        <x-input prefix="Total Bs:" x-bind:value="totalBs.toFixed(2)" readonly suffix="Bs" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 5: Regalías y Aportes --}}
                        <div x-show="tab === 'regalias'" x-transition>
                            <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Regalías</h1>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/3">
                                    <template x-if="metal === 'zn'">
                                        <x-input prefix="Regalía Zn:" x-model="regaliaZn" suffix="%" />
                                    </template>
                                    <template x-if="metal === 'pb'">
                                        <x-input prefix="Regalía Pb:" x-model="regaliaPb" suffix="%" />
                                    </template>
                                </div>
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="=" x-bind:value="regaliaMetalBs.toFixed(2)" readonly suffix="Bs" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="Regalía Ag:" x-model="regaliaAg" suffix="%" />
                                </div>
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="=" x-bind:value="regaliaAgBs.toFixed(2)" readonly suffix="Bs" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="Factor Regalía:" x-model="factorRegalia" />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full">
                                    <x-input prefix="Total RM:" x-bind:value="totalRM.toFixed(2)" readonly suffix="Bs" />
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Aportes</h1>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="CNS:" x-model="cns" suffix="%" />
                                        </div>
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="=" x-bind:value="cnsBs.toFixed(2)" readonly suffix="Bs" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="Comibol:" x-model="comibol" suffix="%" />
                                        </div>
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="=" x-bind:value="comibolBs.toFixed(2)" readonly suffix="Bs" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="Fedecomin:" x-model="fedecomin" suffix="%" />
                                        </div>
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="=" x-bind:value="fedecominBs.toFixed(2)" readonly suffix="Bs" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="Fencomin:" x-model="fencomin" suffix="%" />
                                        </div>
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="=" x-bind:value="fencominBs.toFixed(2)" readonly suffix="Bs" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="Aporte Coop:" x-model="aporteCoop" suffix="%" />
                                        </div>
                                        <div class="w-full sm:w-1/2">
                                            <x-input prefix="=" x-bind:value="aporteCoopBs.toFixed(2)" readonly suffix="Bs" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="w-full sm:w-1/2">
                                        <x-input prefix="Total Aportes:" x-bind:value="totalAportes.toFixed(2)" readonly suffix="Bs" />
                                    </div>
                                    <div class="w-full sm:w-1/2">
                                        <x-input prefix="Costo Final:" x-bind:value="costoFinal.toFixed(2)" readonly suffix="Bs" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button wire:navigate href="{{ route('liquidation.form') }}" text="Cancelar" icon="x-mark"
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
