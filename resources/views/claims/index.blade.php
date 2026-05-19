{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tighter uppercase leading-none">
                    Gestión de <span class="text-yellow-500">Reclamos</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Mortalidad & Notas de Crédito
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- KPIs --}}
        @php
            $allClaims = method_exists($claims, 'getCollection') ? $claims->getCollection() : $claims;
            $totalCount = method_exists($claims, 'total') ? $claims->total() : $claims->count();
            $pendingCount = $allClaims->where('status', 'pending')->count();
            $totalAmount = $allClaims->sum('claim_amount');
            $totalDead = $allClaims->sum('dead_quantity');
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Total Reclamos</p>
                <p class="text-3xl font-black text-white">{{ $totalCount }}</p>
            </div>
            <div class="bg-[#0d121f] border border-amber-500/10 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Pendientes</p>
                <p class="text-3xl font-black {{ $pendingCount > 0 ? 'text-amber-400' : 'text-zinc-600' }}">{{ $pendingCount }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Monto Total</p>
                <p class="text-2xl font-black text-yellow-400">${{ number_format($totalAmount, 0) }}</p>
            </div>
            <div class="bg-[#0d121f] border border-red-500/10 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Bajas Totales</p>
                <p class="text-3xl font-black text-red-400 flex items-center gap-2">
                    <i class="fas fa-kiwi-bird text-sm"></i> {{ number_format($totalDead) }}
                </p>
            </div>
        </div>

        {{-- ACCIONES REQUERIDAS: BAJAS DETECTADAS --}}
        @if($deadConfirmations->count())
        <div class="bg-[#0d121f] border border-red-500/20 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-red-500/10 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-red-400 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    Bajas Detectadas — Requieren Acción
                </h3>
                <span class="text-[9px] font-black text-red-400/60 uppercase tracking-widest">
                    {{ $deadConfirmations->count() }} por procesar
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-6 py-3">Ruta Operativa</th>
                            <th class="px-4 py-3 text-center">Programado</th>
                            <th class="px-4 py-3 text-center">Recibido</th>
                            <th class="px-4 py-3 text-center">Bajas</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @foreach($deadConfirmations as $confirmation)
                        <tr class="hover:bg-red-500/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-truck text-red-500/50 text-xs"></i>
                                    <span class="text-xs font-black text-white uppercase">Ruta #{{ $confirmation->dispatch_route_id }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-xs font-mono text-zinc-500">{{ number_format($confirmation->scheduled_quantity) }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-xs font-mono text-zinc-300">{{ number_format($confirmation->received_quantity) }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-base font-black text-red-400 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-kiwi-bird text-[10px]"></i>
                                    {{ number_format($confirmation->dead_quantity) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <form method="POST" action="{{ route('claims.generate', $confirmation->id) }}">
                                    @csrf
                                    <button class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-xl font-black uppercase text-[9px] tracking-widest transition-all">
                                        Generar Reclamo
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- MONITOR CENTRAL --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                    <i class="fas fa-shield-alt text-yellow-500 mr-2"></i>Monitor Central de Reclamos
                </h3>
                <span class="text-[9px] font-black text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-500/20">
                    Data Live
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-6 py-3">Proveedor / Ruta</th>
                            <th class="px-4 py-3 text-center">Qty Auditoría</th>
                            <th class="px-4 py-3 text-center">Bajas</th>
                            <th class="px-4 py-3 text-right">Monto</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right w-24">Detalle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($claims as $claim)
                        @php
                            $statusStyle = match($claim->status) {
                                'pending'   => ['text-amber-400 bg-amber-500/10 border-amber-500/20', 'Pendiente'],
                                'submitted' => ['text-blue-400 bg-blue-500/10 border-blue-500/20',   'Enviado'],
                                'approved'  => ['text-emerald-400 bg-emerald-500/10 border-emerald-500/20', 'Aprobado'],
                                'rejected'  => ['text-red-400 bg-red-500/10 border-red-500/20',      'Rechazado'],
                                'credited'  => ['text-purple-400 bg-purple-500/10 border-purple-500/20', 'Acreditado'],
                                default     => ['text-zinc-500 bg-white/5 border-white/10',           ucfirst($claim->status)],
                            };
                        @endphp
                        <tr class="hover:bg-white/[0.01] transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-truck text-zinc-700 group-hover:text-yellow-500 transition-colors text-xs"></i>
                                    <div>
                                        <p class="text-xs font-black text-white uppercase tracking-tight">
                                            {{ $claim->provider->business_name ?? 'Sin proveedor' }}
                                        </p>
                                        <p class="text-[9px] font-bold text-zinc-600 mt-0.5">
                                            Ruta #{{ $claim->confirmation->dispatch_route_id ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-[10px] font-mono text-zinc-500">{{ number_format($claim->scheduled_quantity) }}</span>
                                <span class="text-zinc-700 mx-1">/</span>
                                <span class="text-[10px] font-mono text-zinc-300">{{ number_format($claim->received_quantity) }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-base font-black text-red-400 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-kiwi-bird text-[10px] text-red-500/50"></i>
                                    {{ number_format($claim->dead_quantity) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm font-black text-yellow-500">${{ number_format($claim->claim_amount, 0) }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-full border {{ $statusStyle[0] }}">
                                    {{ $statusStyle[1] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('claims.show', $claim->id) }}"
                                   class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-500 flex items-center justify-center transition-all ml-auto">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <i class="fas fa-shield-alt text-4xl"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em]">Sin reclamos registrados</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($claims, 'hasPages') && $claims->hasPages())
                <div class="px-6 py-4 border-t border-white/5 flex justify-end">
                    {{ $claims->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
