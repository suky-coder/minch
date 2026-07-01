<div>
    <x-card>
        <x-slot:header>
            <span class="font-semibold text-base">{{ $this->id ? 'Actualizar' : 'Agregar' }} | Recibo</span>
        </x-slot:header>
        <div class="row ">
            <form id="user-create" wire:submit="save" class="space-y-2">
                <div class="space-y-1">


                    <div x-data="{
                        tab: 'generales',
                        tmh: 22.720,
                        h2o: 11.45,
                        merma: 1,
                        NIM: 1.55,
                        dm: 6.91,
                        zinc: 54.06,
                        Zn: 3434,
                        base: 3000,
                        maquila: 90,
                        basePorcentaje: 0.15,
                        baseEscalacion: 0,
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
                        regaliaZn:3,
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
                        get zincporcentual() {
                            return Number(this.zinc) - 8;
                        },
                        get plataporcentual() {
                            return ((parseFloat(this.dm) * 100) / 31.1035) - 3;
                        },
                        get maquilaS() {
                            return this.zincporcentual * Number(this.Zn) / 100;
                        }, 
                        get platavalue() {
                            return Number(this.AgUSD) * 0.7;
                        },
                        get totalplata() {
                            return this.plataporcentual* this.platavalue;
                        },
                        get baseEscala() {
                            if (Number(this.Zn) > Number(this.base)) {
                                return Number(this.Zn) - Number(this.base);
                            } else {
                                return 0;
                            }
                        },
                        get baseTotal() {
                            if (Number(this.Zn) > Number(this.base)) {
                                return (Number(this.Zn) - Number(this.base)) * Number(this.basePorcentaje);
                            } else {
                                return 0 * Number(this.basePorcentaje);
                            }
                        },
                        get AsTotal(){
                            if(Number(this.As) > Number(this.PAs)){
                                return ((Number(this.As)-Number(this.PAs))*(Number(this.PAsUSD)*Number(this.PAsp)))*100000;
                            }
                            else{
                                return 0;
                            }
                        },
                        get SbTotal(){
                            if(Number(this.Sb) > Number(this.PSb)){
                                return ((Number(this.Sb)-Number(this.PSb))*(Number(this.PSbUSD)*Number(this.PSbp)))*100000;
                            }
                            else{
                                return 0;
                            }
                        },
                        get FeTotal(){
                            if(Number(this.Fe) > Number(this.PFe)){
                                return ((Number(this.Fe)-Number(this.PFe))*(Number(this.PFeUSD)*Number(this.PFep)));
                            }
                            else{
                                return 0;
                            }
                        },
                        get SiO2Total(){
                            if(Number(this.SiO2) > Number(this.PSiO2)){
                                return ((Number(this.SiO2)-Number(this.PSiO2))*(Number(this.PSiO2USD)*Number(this.PSiO2p)));
                            }
                            else{
                                return 0;
                            }
                        },
                        get SnTotal(){
                            if(Number(this.Sn) > Number(this.PSn)){
                                return ((Number(this.Sn)-Number(this.PSn))*(Number(this.PSnUSD)*Number(this.PSnp)));
                            }
                            else{
                                return 0;
                            }
                        },
                        get totalCT() {
                            return parseFloat(this.maquila) + this.baseTotal + this.AsTotal + this.SbTotal + this.FeTotal + this.SiO2Total + this.SnTotal;
                        },
                        get valorNeto() {
                            return Number((this.maquilaS + this.totalplata - this.totalCT).toFixed(2));
                        },
                        get totalNetoUSD() {
                            return Number(this.valorNeto) * Number(this.tmns);
                        },
                        get gastosOp(){
                            return ((this.tmns*1000*Number(this.zinc)/100*2.2046223*Number(this.NIM)*5/100) + (Number(this.dm)*this.tmns/10*32.15073*Number(this.agNIM)*6/100)) -  ((this.tmns*1000*Number(this.zinc)/100*2.2046223*Number(this.NIM)*3/100) + (Number(this.dm)*this.tmns/10*32.15073*Number(this.agNIM)*3.6/100));
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
                        get regaliaZnBs() {
                            return (this.tmns*1000*Number(this.zinc)/100*2.2046223*Number(this.NIM)*Number(this.regaliaZn)/100)*Number(this.factorRegalia);
                        },
                        get regaliaAgBs() {
                            return (Number(this.dm)*this.tmns/10*32.15073*Number(this.agNIM)*Number(this.regaliaAg)/100)*Number(this.factorRegalia)
                        },
                        get totalRM() {
                            return this.regaliaZnBs + this.regaliaAgBs;
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
                                        <x-input prefix="Zn:" type="text" wire:model="NIM" x-model="NIM" suffix="USD/TM" />
                                    </div>
                                    <div class="w-full sm:w-1/2">
                                        <x-input prefix="Zn:" type="text" wire:model="Zn" x-model="Zn" suffix="USD/TM" />
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
                                    <x-input prefix="Sn" suffix="%" x-model="Sn" />
                                </div>
                            </div>

                        </div>

                        {{-- Tab 3: Deducciones --}}
                        <div x-show="tab === 'deducciones'" x-transition>
                            <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Deducciones</h1>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Zinz:" x-bind:value="parseFloat(zinc)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input suffix="%" :value="''" x-bind:value="zincporcentual.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input x-bind:value="parseFloat(Zn)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" value="''" x-bind:value="maquilaS.toFixed(2)" readonly />
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="Plata:" x-bind:value="platacalculate.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input suffix="Oz/Tm" :value="''" x-bind:value="plataporcentual.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input suffix="%" :value="''" x-bind:value="platavalue.toFixed(2)" readonly />
                                </div>
                                <div class="w-full sm:w-1/4">
                                    <x-input prefix="=" :value="''" x-bind:value="totalplata.toFixed(2)" readonly />
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
                                        <x-input prefix="Base/Escalacion:" x-bind:value="parseFloat(Zn).toFixed(2)" readonly />
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
                                        <x-input prefix="=" :value="''" x-bind:value="baseTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-dark-600 my-4 pt-4">
                                <h1 class="text-sm font-semibold text-gray-600 dark:text-dark-300 mb-3">Penalidades</h1>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="As:" :value="''" x-bind:value="parseFloat(As).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PAs" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PAsUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PAsp" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" :value="''" x-bind:value="AsTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Sb:" :value="''" x-bind:value="parseFloat(Sb).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PSb" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PSbUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PSbp" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" :value="''" x-bind:value="SbTotal.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Fe:" :value="''" x-bind:value="parseFloat(Fe).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PFe" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PFeUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PFep" suffix="%" />
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
                                        <x-input :value="''" x-model="PSiO2" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PSiO2USD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PSiO2p" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" :value="''" x-bind:value="SiO2Total.toFixed(2)" readonly />
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="Sn:" :value="''" x-bind:value="parseFloat(Sn).toFixed(2)" readonly />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PSn" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="USD:" x-model="PSnUSD" suffix="/TMS" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input :value="''" x-model="PSnp" suffix="%" />
                                    </div>
                                    <div class="w-full sm:w-1/5">
                                        <x-input prefix="=" :value="''" x-bind:value="SnTotal.toFixed(2)" readonly />
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
                                    <x-input prefix="Regalía Zn:" x-model="regaliaZn" suffix="%" />
                                </div>
                                <div class="w-full sm:w-1/3">
                                    <x-input prefix="=" x-bind:value="regaliaZnBs.toFixed(2)" readonly suffix="Bs" />
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
