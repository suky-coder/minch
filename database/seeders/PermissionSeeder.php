<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions=[
                ['guard_name' => 'web', 'name' => 'Crear usuarios', 'description' => 'Puede crear nuevos usuarios'],
                ['guard_name' => 'web', 'name' => 'Editar usuarios', 'description' => 'Puede editar la información de los usuarios'],
                ['guard_name' => 'web', 'name' => 'Eliminar usuarios', 'description' => 'Puede eliminar registros de usuarios'],
                ['guard_name' => 'web', 'name' => 'Ver usuarios', 'description' => 'Puede ver la lista y los detalles de usuarios'],


                ['guard_name' => 'web', 'name' => 'Ver roles', 'description' => 'Puede ver la lista y los detalles de roles'],
                ['guard_name' => 'web', 'name' => 'Editar roles', 'description' => 'Puede editar los datos de los roles'],
                ['guard_name' => 'web', 'name' => 'Eliminar roles', 'description' => 'Puede Eliminar roles existentes'],
                ['guard_name' => 'web', 'name' => 'Crear roles', 'description' => 'Puede crear nuevos roles'],
                ['guard_name' => 'web', 'name' => 'Asignación de permisos', 'description' => 'Puede controlar los permisos de cada tipo de usuarios'],




                ['guard_name' => 'web', 'name' => 'Crear retenciones', 'description' => 'Puede crear nuevos retenciones'],
                ['guard_name' => 'web', 'name' => 'Editar retenciones', 'description' => 'Puede editar la información de los retenciones existentes'],
                ['guard_name' => 'web', 'name' => 'Eliminar retenciones', 'description' => 'Puede eliminar retenciones existentes'],
                ['guard_name' => 'web', 'name' => 'Ver retenciones', 'description' => 'Puede ver la lista y los detalles de retenciones'],
                ['guard_name' => 'web', 'name' => 'Exportar retenciones', 'description' => 'Puede exportar las retenciones de una determinada fecha en formato Excel'],



                ['guard_name' => 'web', 'name' => 'Crear impuestos', 'description' => 'Puede crear nuevos impuestos'],
                ['guard_name' => 'web', 'name' => 'Editar impuestos', 'description' => 'Puede editar la información de los impuestos'],
                ['guard_name' => 'web', 'name' => 'Eliminar impuestos', 'description' => 'Puede eliminar registros de impuestos'],
                ['guard_name' => 'web', 'name' => 'Ver impuestos', 'description' => 'Puede ver la lista y los detalles de impuestos'],


                ['guard_name' => 'web', 'name' => 'Crear cuentas', 'description' => 'Puede crear nuevas cuentas'],
                ['guard_name' => 'web', 'name' => 'Editar cuentas', 'description' => 'Puede editar la información de las cuentas'],
                ['guard_name' => 'web', 'name' => 'Eliminar cuentas', 'description' => 'Puede eliminar registros de cuentas'],
                ['guard_name' => 'web', 'name' => 'Ver cuentas', 'description' => 'Puede ver la lista y los detalles de las  cuentas'],


                ['guard_name' => 'web', 'name' => 'Ver libro de bancos', 'description' => 'Puede ver la lista y los detalles del libro de bancos'],
                ['guard_name' => 'web', 'name' => 'PDF libro de bancos', 'description' => 'Puede exportar el libro de bancos en formato PDF'],
                ['guard_name' => 'web', 'name' => 'Excel libro de bancos', 'description' => 'Puede expotar el libro de bancos en formato Excel'],

                ['guard_name' => 'web', 'name' => 'Crear movimientos de libro de bancos', 'description' => 'Puede crear nuevos movimientos de libro de bancos'],
                ['guard_name' => 'web', 'name' => 'Editar movimientos de libro de bancos', 'description' => 'Puede editar la información de un movimiento del libro de bancos'],
                ['guard_name' => 'web', 'name' => 'Eliminar movimientos de libro de bancos', 'description' => 'Puede eliminar movimientos de libro de bancos'],
                ['guard_name' => 'web', 'name' => 'Ver movimiento de libro de bancos ', 'description' => 'Puede ver el detalle un movimiento en formato PDF'],

                ['guard_name' => 'web', 'name' => 'Ver estados de cuenta', 'description' => 'Puede ver la lista y el detalle de estados de cuenta de clientes y proveedores'],
                ['guard_name' => 'web', 'name' => 'Crear estados de cuenta', 'description' => 'Puede registrar clientes, proveedores y movimientos en estados de cuenta'],
                ['guard_name' => 'web', 'name' => 'Editar estados de cuenta', 'description' => 'Puede editar clientes, proveedores y movimientos de estados de cuenta'],
                ['guard_name' => 'web', 'name' => 'Eliminar estados de cuenta', 'description' => 'Puede eliminar clientes, proveedores y movimientos de estados de cuenta'],
                ['guard_name' => 'web', 'name' => 'PDF estados de cuenta', 'description' => 'Puede exportar estados de cuenta en formato PDF'],

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['description' => $permission['description']]
            );
        }

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::pluck('name'));
        }
    }
}
