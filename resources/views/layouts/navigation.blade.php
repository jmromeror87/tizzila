{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<style>
    .sidebar-dropdown div[x-show] {
        background-color: #0d121f !important;
        border: 1px solid rgba(243, 196, 68, 0.2) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
        border-radius: 1rem !important;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(243, 196, 68, 0.2); border-radius: 10px; }

    .nav-item-active  { background: #f3c444; color: #070a13 !important; }
    .nav-item-active i { color: #070a13 !important; }
    .nav-group-active { color: #f3c444 !important; background: rgba(243,196,68,0.07); border-color: rgba(243,196,68,0.12) !important; }
    .nav-group-active i { color: #f3c444 !important; }
    .nav-section { font-size: 9px; font-weight: 900; letter-spacing: .25em; text-transform: uppercase; color: #374151; padding: 0 1rem; margin-top: 1.25rem; margin-bottom: .4rem; display: flex; align-items: center; gap: .5rem; }
    .nav-section::after { content:''; flex:1; height:1px; background:rgba(255,255,255,0.04); }
</style>

@php
    $u = auth()->user();
    $is = fn(string|array $roles) => $u->hasRole($roles);
    $can = fn(string $mod) => $u->canModule($mod);
@endphp

<div x-data="{ sidebarOpen: false }" class="relative">

    {{-- Botón Móvil --}}
    <div class="lg:hidden fixed top-4 left-4 z-[60]">
        <button @click="sidebarOpen = !sidebarOpen"
            class="p-3 rounded-xl bg-[#f3c444] text-[#070a13] shadow-lg shadow-[#f3c444]/30 active:scale-95 transition-all">
            <i class="fas" :class="sidebarOpen ? 'fa-times' : 'fa-bars'"></i>
        </button>
    </div>

    {{-- Overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

    <nav :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="w-64 h-screen bg-[#070a13] border-r border-white/5 flex flex-col fixed left-0 top-0 z-50 transition-transform duration-300 ease-in-out">

        {{-- Branding --}}
        <div class="shrink-0 px-5 py-5 border-b border-white/5 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                <div class="h-9 w-9 rounded-xl bg-[#f3c444]/10 border border-[#f3c444]/20 flex items-center justify-center">
                    <i class="fas fa-egg text-[#f3c444] text-base transition-transform group-hover:scale-110"></i>
                </div>
                <span class="text-white font-extrabold text-lg tracking-wide">
                    Tizzila<span class="text-[#f3c444]">App</span>
                </span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-white">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- Menú con scroll --}}
        <div class="flex-1 px-3 py-4 overflow-y-auto custom-sidebar-scrollbar sidebar-dropdown space-y-0.5">

            {{-- ═══════════════════════════════════
                 INICIO
            ═══════════════════════════════════ --}}
            <div class="nav-section"><i class="fas fa-home opacity-40 text-[8px]"></i>Inicio</div>

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all
                    {{ request()->routeIs('dashboard') ? 'nav-item-active shadow-lg shadow-[#f3c444]/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-th-large text-xs w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            @if($can('clientes'))
            <a href="{{ route('customers.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all
                    {{ request()->routeIs('customers.*') ? 'nav-item-active shadow-lg shadow-[#f3c444]/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-users text-xs w-4 text-center"></i>
                <span>Clientes</span>
            </a>
            @endif

            {{-- ═══════════════════════════════════
                 OPERACIONES  (operaciones, admin)
            ═══════════════════════════════════ --}}
            @if($can('programacion') || $can('logistica'))
            <div class="nav-section"><i class="fas fa-truck opacity-40 text-[8px]"></i>Operaciones</div>
            @endif

            @if($can('programacion'))
            <div x-data="{ open: {{ request()->routeIs('poultry.orders.*','claims.*','poultry.provider*','poultry.providers*') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('poultry.orders.*','claims.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-calendar-check text-xs w-4 text-center"></i>
                        <span>Programación</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">
                    <a href="{{ route('poultry.orders.calendar') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('poultry.orders.calendar') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-calendar-alt w-3.5 text-center"></i>Calendario de Pedidos
                    </a>
                    <a href="{{ route('poultry.orders.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('poultry.orders.index') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-list-ol w-3.5 text-center"></i>Lista de Pedidos
                    </a>
                    <a href="{{ route('poultry.providers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('poultry.providers.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-industry w-3.5 text-center"></i>Proveedores
                    </a>
                    <a href="{{ route('poultry.provider-documents.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('poultry.provider-documents.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-file-contract w-3.5 text-center"></i>Docs. Proveedor
                    </a>
                    <a href="{{ route('purchase-invoices.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('purchase-invoices.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-file-invoice-dollar w-3.5 text-center"></i>Ctas. por Pagar
                    </a>
                    <a href="{{ route('poultry.projection') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('poultry.projection') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-chart-line w-3.5 text-center"></i>Proyección 3 Meses
                    </a>
                    <a href="{{ route('claims.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('claims.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-exclamation-circle w-3.5 text-center"></i>Reclamos
                    </a>
                </div>
            </div>
            @endif

            @if($can('logistica'))
            <div x-data="{ open: {{ request()->routeIs('dispatch.*','poultry.drivers.*') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('dispatch.*','poultry.drivers.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-truck text-xs w-4 text-center"></i>
                        <span>Logística</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">
                    <a href="{{ route('dispatch.routes.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('dispatch.routes.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-route w-3.5 text-center"></i>Centro de Rutas
                    </a>
                    <a href="{{ route('poultry.drivers.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('poultry.drivers.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-id-card w-3.5 text-center"></i>Conductores
                    </a>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════
                 FINANZAS  (finanzas, gerencia, admin)
            ═══════════════════════════════════ --}}
            @if($can('facturacion') || $can('cartera') || $can('pagos') || $can('gastos') || $can('contabilidad'))
            <div class="nav-section"><i class="fas fa-coins opacity-40 text-[8px]"></i>Finanzas</div>
            @endif

            @if($can('facturacion'))
            <div x-data="{ open: {{ request()->routeIs('invoices.*') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('invoices.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-invoice-dollar text-xs w-4 text-center"></i>
                        <span>Facturación</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">
                    <a href="{{ route('invoices.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('invoices.create') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-plus-circle w-3.5 text-center"></i>Nueva Factura
                    </a>
                    <a href="{{ route('invoices.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('invoices.index') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-list-alt w-3.5 text-center"></i>Historial
                    </a>
                </div>
            </div>
            @endif

            @if($can('cartera'))
            <div x-data="{ open: {{ request()->routeIs('cartera.*') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('cartera.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-signature text-xs w-4 text-center"></i>
                        <span>Cartera</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">
                    <a href="{{ route('cartera.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('cartera.index') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-hand-holding-usd w-3.5 text-center"></i>Cuentas por Cobrar
                    </a>
                    <a href="{{ route('cartera.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('cartera.dashboard') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-chart-pie w-3.5 text-center"></i>Estado de Cartera
                    </a>
                </div>
            </div>
            @endif

            @if($can('pagos'))
            <a href="{{ route('payments.all') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all
                    {{ request()->routeIs('payments.*') ? 'nav-item-active shadow-lg shadow-[#f3c444]/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-receipt text-xs w-4 text-center"></i>
                <span>Pagos</span>
            </a>
            @endif

            @if($can('gastos'))
            <div x-data="{ open: {{ request()->routeIs('expenses.*','recurring-expenses.*') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('expenses.*','recurring-expenses.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-money-bill-wave text-xs w-4 text-center"></i>
                        <span>Gastos</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">
                    <a href="{{ route('expenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('expenses.index') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-list w-3.5 text-center"></i>Historial de Gastos
                    </a>
                    <a href="{{ route('recurring-expenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('recurring-expenses.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-sync-alt w-3.5 text-center"></i>Recurrentes
                    </a>
                    <a href="{{ route('expenses.calendar') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('expenses.calendar') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-calendar-times w-3.5 text-center"></i>Obligaciones
                    </a>
                </div>
            </div>
            @endif

            @if($can('contabilidad'))
            <div x-data="{ open: {{ request()->routeIs('accounting.*','journal.*','ledger.*','trial.*','income.*','balance.*') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('accounting.*','journal.*','ledger.*','trial.*','income.*','balance.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-book text-xs w-4 text-center"></i>
                        <span>Contabilidad</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">
                    <a href="{{ route('accounting.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('accounting.dashboard') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-tachometer-alt w-3.5 text-center"></i>Tablero
                    </a>
                    <a href="{{ route('journal.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('journal.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-book-open w-3.5 text-center"></i>Libro Diario
                    </a>
                    <a href="{{ route('ledger.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('ledger.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-book w-3.5 text-center"></i>Libro Mayor
                    </a>
                    <div class="pt-1 pb-0.5 px-2">
                        <p class="text-[8px] font-black uppercase tracking-widest text-gray-700">Estados Financieros</p>
                    </div>
                    <a href="{{ route('trial.balance') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('trial.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-balance-scale w-3.5 text-center"></i>Balance Comprobación
                    </a>
                    <a href="{{ route('income.statement') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('income.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-chart-line w-3.5 text-center"></i>Estado de Resultados
                    </a>
                    <a href="{{ route('balance.sheet') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('balance.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-landmark w-3.5 text-center"></i>Balance General
                    </a>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════
                 SISTEMA  (solo admin)
            ═══════════════════════════════════ --}}
            @if($can('configuracion'))
            <div class="nav-section"><i class="fas fa-cog opacity-40 text-[8px]"></i>Sistema</div>

            <div x-data="{ open: {{ request()->routeIs('setup.*','whatsapp.*','admin.*') || str_contains(request()->path(), 'configuration') || str_contains(request()->path(), 'poultry/types') || str_contains(request()->path(), 'tax-categories') || str_contains(request()->path(), 'accounting/settings') || str_contains(request()->path(), 'accounting/chart') ? 'true':'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border
                        {{ request()->routeIs('setup.*','whatsapp.*','admin.*') ? 'nav-group-active border-[#f3c444]/12' : 'text-gray-400 hover:text-white hover:bg-white/5 border-transparent' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-cog text-xs w-4 text-center"></i>
                        <span>Configuración</span>
                    </div>
                    <i class="fas fa-chevron-right text-[9px] opacity-40 transition-transform duration-200" :class="open && 'rotate-90'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-cloak
                    class="ml-4 mt-0.5 space-y-0.5 border-l border-white/5 pl-3">

                    {{-- Empresa --}}
                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-700 px-2 pt-1">Empresa</p>
                    <a href="{{ route('setup.company') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('setup.company') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-building w-3.5 text-center"></i>Datos de la Empresa
                    </a>
                    <a href="/configuration" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-white hover:bg-white/5">
                        <i class="fas fa-sliders-h w-3.5 text-center"></i>Panel Global
                    </a>

                    {{-- Catálogos --}}
                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-700 px-2 pt-2">Catálogos</p>
                    <a href="/poultry/types" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-white hover:bg-white/5">
                        <i class="fas fa-dove w-3.5 text-center"></i>Tipos de Aves
                    </a>
                    <a href="/tax-categories" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-white hover:bg-white/5">
                        <i class="fas fa-percent w-3.5 text-center"></i>Impuestos
                    </a>
                    <a href="/accounting/settings" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->is('accounting/settings') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-calculator w-3.5 text-center"></i>Config. Contable
                    </a>
                    <a href="{{ route('accounting.chart.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('accounting.chart.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-sitemap w-3.5 text-center"></i>Plan de Cuentas
                    </a>

                    {{-- Integraciones --}}
                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-700 px-2 pt-2">Integraciones</p>
                    <a href="{{ route('whatsapp.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('whatsapp.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fab fa-whatsapp w-3.5 text-center"></i>WhatsApp
                    </a>

                    {{-- Acceso --}}
                    <p class="text-[8px] font-black uppercase tracking-widest text-gray-700 px-2 pt-2">Acceso</p>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-users w-3.5 text-center"></i>Usuarios
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.permissions.*') ? 'text-[#f3c444] bg-[#f3c444]/5' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-shield-alt w-3.5 text-center"></i>Permisos & Roles
                    </a>
                </div>
            </div>
            @endif

        </div>

        {{-- Perfil (fijo abajo) --}}
        <div class="shrink-0 p-3 border-t border-white/5 bg-[#070a13]">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/[0.03] border border-white/5">
                <div class="h-8 w-8 rounded-lg bg-[#f3c444] flex items-center justify-center text-black font-black text-sm shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="text-xs font-bold text-white truncate leading-tight">{{ Auth::user()->name }}</span>
                    <span class="text-[9px] text-[#f3c444] font-bold uppercase tracking-tight">{{ auth()->user()->role?->label ?? 'Sin rol' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar sesión"
                        class="h-8 w-8 rounded-lg flex items-center justify-center text-red-500/60 hover:text-red-400 hover:bg-red-500/10 transition-all">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>
