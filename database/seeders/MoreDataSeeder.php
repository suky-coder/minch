<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Box;
use App\Models\Discount;
use App\Models\Movement;
use App\Models\Person;
use App\Models\Retention;
use App\Models\Supplier;
use App\Models\Taxe;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoreDataSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()?->id ?? 1;
        $taxes = Taxe::all();
        $suppliers = Supplier::with('person')->get();
        $accounts = Account::all();

        // ─── 50 Retentions ───
        $retentionTypes = ['S', 'G'];
        $statuses = ['0', '1'];
        $descriptions = [
            'RETENCIÓN POR SERVICIOS DE INGENIERÍA',
            'RETENCIÓN POR COMPRA DE REPUESTOS',
            'RETENCIÓN POR SERVICIOS DE AUDITORÍA',
            'RETENCIÓN POR ADQUISICIÓN DE SOFTWARE',
            'RETENCIÓN POR SERVICIOS LEGALES',
            'RETENCIÓN POR COMPRA DE MAQUINARIA PESADA',
            'RETENCIÓN POR SERVICIOS DE CONSULTORÍA AMBIENTAL',
            'RETENCIÓN POR SUMINISTRO DE COMUNICACIONES',
            'RETENCIÓN POR SERVICIOS DE VIGILANCIA',
            'RETENCIÓN POR COMPRA DE VEHÍCULOS',
            'RETENCIÓN POR SERVICIOS DE PUBLICIDAD',
            'RETENCIÓN POR ALQUILER DE EQUIPOS',
            'RETENCIÓN POR SERVICIOS DE TOPOGRAFÍA',
            'RETENCIÓN POR COMPRA DE LABORATORIO',
            'RETENCIÓN POR SERVICIOS DE SEGUROS',
            'RETENCIÓN POR COMPRA DE EQUIPO MÉDICO',
            'RETENCIÓN POR SERVICIOS DE TRADUCCIÓN',
            'RETENCIÓN POR MANTENIMIENTO DE VEHÍCULOS',
            'RETENCIÓN POR SERVICIOS DE CATERING',
            'RETENCIÓN POR COMPRA DE MOBILIARIO',
            'RETENCIÓN POR SERVICIOS DE DISEÑO GRÁFICO',
            'RETENCIÓN POR COMPRA DE ROPA DE TRABAJO',
            'RETENCIÓN POR SERVICIOS DE JARDINERÍA',
            'RETENCIÓN POR COMPRA DE EQUIPO DE CÓMPUTO',
            'RETENCIÓN POR SERVICIOS DE FUMIGACIÓN',
            'RETENCIÓN POR COMPRA DE CÁMARAS',
            'RETENCIÓN POR SERVICIOS DE MENSAJERÍA',
            'RETENCIÓN POR ALQUILER DE OFICINAS',
            'RETENCIÓN POR SERVICIOS DE RECURSOS HUMANOS',
            'RETENCIÓN POR COMPRA DE SEÑALIZACIÓN',
            'RETENCIÓN POR SERVICIOS DE LAVANDERÍA',
            'RETENCIÓN POR COMPRA DE EQUIPO DE SONIDO',
            'RETENCIÓN POR SERVICIOS DE EVENTOS',
            'RETENCIÓN POR MANTENIMIENTO DE EDIFICIOS',
            'RETENCIÓN POR SERVICIOS DE ARCHIVO',
            'RETENCIÓN POR COMPRA DE BOTIQUINES',
            'RETENCIÓN POR SERVICIOS DE FOTOCOPIADO',
            'RETENCIÓN POR COMPRA DE EXTINTORES',
            'RETENCIÓN POR SERVICIOS DE CUSTODIA',
            'RETENCIÓN POR COMPRA DE AIRE ACONDICIONADO',
            'RETENCIÓN POR SERVICIOS DE IMPRESIÓN',
            'RETENCIÓN POR COMPRA DE GENERADORES',
            'RETENCIÓN POR SERVICIOS DE ECOGRAFÍA',
            'RETENCIÓN POR COMPRA DE BOMBAS',
            'RETENCIÓN POR SERVICIOS DE SOLDADURA',
            'RETENCIÓN POR COMPRA DE CABLES',
            'RETENCIÓN POR SERVICIOS DE PODA',
            'RETENCIÓN POR COMPRA DE LUBRICANTES',
            'RETENCIÓN POR SERVICIOS DE DRENAJE',
            'RETENCIÓN POR COMPRA DE FILTROS',
        ];

        for ($i = 0; $i < 50; $i++) {
            $supplier = $suppliers->random();
            $date = Carbon::now()->subDays(rand(1, 365));
            $type = $retentionTypes[array_rand($retentionTypes)];
            $amount = rand(1000, 50000);

            DB::transaction(function () use ($descriptions, $i, $supplier, $date, $type, $amount, $userId, $taxes, $statuses) {
                $retention = Retention::create([
                    'description' => $descriptions[$i],
                    'summary' => $descriptions[$i],
                    'amount' => $amount,
                    'code' => (int) (($i + 16).now()->format('dHis')),
                    'status' => $statuses[array_rand($statuses)],
                    'type' => $type,
                    'date' => $date,
                    'supplier_id' => $supplier->id,
                    'user_id' => $userId,
                ]);

                $numDiscounts = rand(1, 3);
                for ($d = 0; $d < $numDiscounts; $d++) {
                    $taxe = $taxes->random();
                    $discountAmount = round($amount * $taxe->applied_discount / 100 / $numDiscounts, 2);
                    $discountAmount = min($discountAmount, 9999.99);
                    Discount::create([
                        'amount' => $discountAmount,
                        'taxe_id' => $taxe->id,
                        'retention_id' => $retention->id,
                    ]);
                }
            });
        }

        // ─── 30 Cash (Box) Movements ───
        $cashDescriptions = [
            'PAGO DE HONORARIOS PROFESIONALES',
            'COMPRA DE ARTÍCULOS DE FERRETERÍA',
            'REEMBOLSO DE GASTOS DE VIAJE',
            'PAGO POR SERVICIO DE FOTOCOPIAS',
            'COBRO POR VENTA DE ACTIVOS FIJOS',
            'PAGO DE GASTOS DE CAPACITACIÓN',
            'COMPRA DE EXTINTORES',
            'PAGO DE ARBITRIOS MUNICIPALES',
            'COBRO POR ARRENDAMIENTO DE EQUIPOS',
            'PAGO DE SERVICIOS CONTABLES',
            'COMPRA DE MATERIAL DIDÁCTICO',
            'PAGO DE GASTOS DE TRANSPORTE',
            'COBRO POR VENTA DE CHATARRA',
            'PAGO DE SERVICIOS MÉDICOS',
            'COMPRA DE ARTÍCULOS DE COCINA',
            'PAGO DE GASTOS NOTARIALES',
            'COBRO POR SERVICIOS DE ESTACIONAMIENTO',
            'PAGO DE VIÁTICOS',
            'COMPRA DE BOTIQUÍN DE PRIMEROS AUXILIOS',
            'PAGO DE GASTOS JUDICIALES',
            'COBRO POR VENTA DE MATERIAL RECICLABLE',
            'PAGO DE SUSCRIPCIONES',
            'COMPRA DE CORTINAS Y PERSIANAS',
            'PAGO DE GASTOS DE REPARACIÓN',
            'COBRO POR ALQUILER DE SALAS',
            'PAGO DE CUOTAS DE AFILIACIÓN',
            'COMPRA DE EQUIPO DE PROTECCIÓN',
            'PAGO DE GASTOS DE ENVÍO',
            'COBRO POR SERVICIOS DE CATERING',
            'PAGO DE LICENCIAS DE SOFTWARE',
        ];

        $allPeople = Person::all();

        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(rand(1, 180));
            $type = rand(0, 3) > 0 ? 'D' : 'C';
            $amount = $type === 'D' ? rand(1000, 30000) : rand(500, 15000);

            DB::transaction(function () use ($date, $type, $amount, $cashDescriptions, $i, $userId, $allPeople) {
                $movement = Movement::create([
                    'date' => $date,
                    'description' => $cashDescriptions[$i],
                    'type' => $type,
                    'amount' => $amount,
                    'person_id' => rand(0, 4) > 0 ? $allPeople->random()->id : null,
                    'user_id' => $userId,
                ]);

                Box::create(['movement_id' => $movement->id]);
            });
        }

        // ─── 30 Bank (Transaction) Movements ───
        $bankDescriptions = [
            'TRANSFERENCIA POR SERVICIOS DE INGENIERÍA',
            'PAGO DE DIVIDENDOS A ACCIONISTAS',
            'COBRO DE DEVOLUCIÓN DE IMPUESTOS',
            'PAGO DE PRIMA DE SEGUROS',
            'DEPÓSITO DE FONDOS DE TERCEROS',
            'PAGO DE REGALÍAS MINERAS',
            'TRANSFERENCIA POR VENTA DE ACTIVOS',
            'PAGO DE GASTOS ADMINISTRATIVOS',
            'COBRO POR SERVICIOS DE ASESORAMIENTO',
            'PAGO DE OBLIGACIONES TRIBUTARIAS',
            'TRANSFERENCIA POR COMPRA DE TÍTULOS',
            'PAGO DE INTERESES POR PRÉSTAMO',
            'COBRO DE INDEMNIZACIONES',
            'PAGO DE COMPROMISOS SINDICALES',
            'TRANSFERENCIA POR SERVICIOS TÉCNICOS',
            'PAGO DE GASTOS OPERATIVOS',
            'COBRO POR ARRIENDO DE MAQUINARIA',
            'PAGO DE SERVICIOS PORTUARIOS',
            'DEPÓSITO DE GARANTÍA',
            'PAGO DE CUENTAS POR COBRAR',
            'TRANSFERENCIA POR COMPRA DE INVENTARIO',
            'PAGO DE SERVICIOS AEROPORTUARIOS',
            'COBRO DE FIANZAS',
            'PAGO DE GASTOS DE IMPORTACIÓN',
            'TRANSFERENCIA POR SERVICIOS LOGÍSTICOS',
            'PAGO DE DERECHOS DE SUPERFICIE',
            'COBRO DE SUBSIDIOS',
            'PAGO DE COMISIONES BANCARIAS',
            'DEPÓSITO DE FONDOS RESERVADOS',
            'PAGO DE CONCESIONES MINERAS',
        ];

        $paymentTypes = ['CH', 'T'];

        for ($i = 0; $i < 30; $i++) {
            $account = $accounts->random();
            $date = Carbon::now()->subDays(rand(1, 180));
            $type = rand(0, 3) > 0 ? 'D' : 'C';
            $amount = $type === 'D' ? rand(5000, 100000) : rand(2000, 50000);
            $paymentType = $paymentTypes[array_rand($paymentTypes)];

            DB::transaction(function () use ($account, $date, $type, $amount, $paymentType, $bankDescriptions, $i, $userId, $allPeople) {
                $movement = Movement::create([
                    'date' => $date,
                    'description' => $bankDescriptions[$i],
                    'type' => $type,
                    'amount' => $amount,
                    'person_id' => rand(0, 4) > 0 ? $allPeople->random()->id : null,
                    'user_id' => $userId,
                ]);

                Transaction::create([
                    'payment_type' => $paymentType,
                    'account_id' => $account->id,
                    'movement_id' => $movement->id,
                    'number_check' => $paymentType === 'CH' ? 'CH-'.rand(1000, 9999) : 'TRF-'.rand(10000, 99999),
                ]);
            });
        }
    }
}
