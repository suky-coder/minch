<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-cloak x-data="{ name: @js(auth()->user()->name) }" x-on:name-updated.window="name = $event.detail.name"
    x-bind:class="{ 'dark bg-gray-800': darkTheme, 'bg-gray-100': !darkTheme }">
    <x-layout>
        <x-slot:top>
            <x-dialog />
            <x-toast />
        </x-slot:top>
        <x-slot:header>
            <x-layout.header>
                <x-slot:right>
                    <x-dropdown>
                        <x-slot:action>
                            <div>
                                <button class="cursor-pointer" x-on:click="show = !show">
                                    <span class="text-base font-semibold text-primary-500" x-text="name"></span>
                                </button>
                            </div>
                        </x-slot:action>
                        <x-slot:header>
                            <x-theme-switch block />
                        </x-slot:header>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown.items :text="__('Profile')" :href="route('dashboard')" />
                            <x-dropdown.items :text="__('Logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" separator />
                        </form>
                    </x-dropdown>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>
        <x-slot:menu>
            <x-side-bar smart collapsible>
                <x-slot:brand>
                    <div class="mt-4 mb-1 flex items-center justify-center">
                        <img src="{{ asset('image/logo.png') }}" width="230" height="230" alt="Logo" />
                    </div>
                    <div class="my-1 flex items-center justify-center ">
                        <h1 class="font-sans text-2xl font-bold text-center tracking-wide text-black dark:text-white">
                            Empresa MINCH SRL.
                        </h1>
                    </div>
                </x-slot:brand>
                <x-slot:brand-collapsed>
                    <div class="mt-4 mb-1 flex items-center justify-center">
                        <img src="{{ asset('image/logo.png') }}" width="70" height="70" alt="Logo" />
                    </div>
                    <h1 class="text-black dark:text-white text-xs text-center">
                        MINCH SRL.
                    </h1>
                </x-slot:brand-collapsed>
                <!-- resto del contenido del sidebar -->
                <x-side-bar.item text="Dashboard" icon="home" :route="route('dashboard')" wire:navigate />
                <x-side-bar.item text="Control de Usuario" icon="users">
                    @can('Ver usuarios')
                    <x-side-bar.item text="Usuarios" icon="user" :route="route('users')" wire:navigate/>
                        
                    @endcan
                    @can('Ver roles')
                    <x-side-bar.item text="Roles" icon="user-group" :route="route('roles')" wire:navigate/>
                        
                    @endcan
                    @can('Asignación de permisos')
                    <x-side-bar.item text="Permisos" icon="user-minus" :route="route('permissions')" wire:navigate/>
                    @endcan
                </x-side-bar.item>
                <x-side-bar.item text="Impuestos" icon="percent-badge" :route="route('taxes')" wire:navigate />
                <x-side-bar.item text="Proveedores" icon="building-storefront" :route="route('suppliers')" wire:navigate />
                <x-side-bar.item text="Cooperativas" icon="building-storefront" :route="route('cooperatives')" wire:navigate />
                <x-side-bar.item text="Cuentas" icon="clipboard-document-check" :route="route('accounts')" wire:navigate />
                <x-side-bar.item text="Retenciones" icon="hand-raised" :route="route('retentions')" wire:navigate />
                <x-side-bar.item text="Tesoreria" icon="credit-card">
                    @can('Ver libro de bancos')
                    <x-side-bar.item text="Libro de bancos" icon="user" :route="route('transactions')" wire:navigate />
                    @endcan
                    <x-side-bar.item text="Libro de cajas" icon="user-group" :route="route('accounts.box')" wire:navigate />
                    @can('Ver estados de cuenta')
                    <x-side-bar.item text="Estado de cuenta" icon="document-text" :route="route('accounts.statement')" wire:navigate />
                    @endcan
                </x-side-bar.item>
{{--                 <x-side-bar.item text="Liquidaciones" icon="building-storefront" :route="route('liquidation.form')" wire:navigate />
 --}}{{--                 <x-side-bar.item text="Movimientos" icon="credit-card" :route="route('transactions')" wire:navigate />   --}}                
                <x-side-bar.item text="Reportes" icon="chart-bar">
                    <x-side-bar.item text="Retencion" icon="user" :route="route('users')" />
                    <x-side-bar.item text="Caja" icon="user-group" :route="route('users')" />
                </x-side-bar.item>
                <x-side-bar.item text="Welcome Page" icon="arrow-uturn-left" :route="route('dashboard')" />
            </x-side-bar>
        </x-slot:menu>
        {{ $slot }}
    </x-layout>
    @livewireScripts
</body>
<script>
    function confirmDelete(id) {
        const component = Livewire.getByName('nombre-de-tu-componente')[0];

        $tsui.interaction('dialog')
            .wireable(component)
            .question('Advertencia', '¿Estás seguro de eliminar este registro?')
            .confirm('Confirmar', 'delete', [id])
            .cancel('Cancelar')
            .send();
    }
</script>

</html>
