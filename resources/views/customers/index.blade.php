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
                <h2 class="text-3xl font-black text-white tracking-tighter uppercase">
                    Directorio de <span class="text-yellow-500">Clientes</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Base de datos sincronizada · DIAN
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar NIT o nombre..."
                        class="pl-9 pr-4 py-2.5 rounded-xl bg-[#0d121f] border border-white/10 text-xs text-white placeholder-zinc-700 outline-none focus:border-yellow-500/50 w-56">
                </form>
                <a href="{{ route('customers.create') }}"
                   class="bg-yellow-500 hover:bg-yellow-400 text-black font-black py-2.5 px-5 rounded-xl transition-all text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                    <i class="fas fa-plus"></i> Nuevo Cliente
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- Tabla --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden shadow-2xl">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                    <i class="fas fa-users text-yellow-500 mr-2"></i>Clientes Registrados
                </h3>
                <span class="text-[10px] font-black text-zinc-500">{{ $customers->total() }} clientes</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-4 py-3 text-center">Identificación</th>
                            <th class="px-4 py-3 text-right">Facturado</th>
                            <th class="px-4 py-3 text-right">Saldo</th>
                            <th class="px-4 py-3 text-center">Score</th>
                            <th class="px-4 py-3">Contacto</th>
                            <th class="px-4 py-3 text-right w-24">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($customers as $customer)
                            @php
                                $invoiced  = $customer->invoices_sum_total ?? 0;
                                $balance   = $customer->invoices_sum_balance ?? 0;
                                $paid      = $invoiced - $balance;
                                $score     = $invoiced > 0 ? round(($paid / $invoiced) * 100) : 0;
                                if ($score >= 90)      { $sc = 'text-emerald-400'; $sb = 'bg-emerald-500'; }
                                elseif ($score >= 70)  { $sc = 'text-blue-400';   $sb = 'bg-blue-500'; }
                                elseif ($score >= 40)  { $sc = 'text-yellow-400'; $sb = 'bg-yellow-500'; }
                                else                   { $sc = 'text-red-400';    $sb = 'bg-red-500'; }
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-zinc-900 border border-white/5 group-hover:border-yellow-500/20 flex items-center justify-center shrink-0 transition-all">
                                            <span class="text-xs font-black text-yellow-500">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-white uppercase tracking-tight group-hover:text-yellow-500 transition-colors">{{ $customer->name }}</p>
                                            <p class="text-[9px] text-zinc-600 font-bold mt-0.5">
                                                {{ $customer->invoices_count }} factura{{ $customer->invoices_count !== 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-mono text-xs text-zinc-300 bg-black/40 px-2 py-1 rounded-lg border border-white/5">
                                        {{ $customer->identification_number }}@if($customer->dv)<span class="text-yellow-500">-{{ $customer->dv }}</span>@endif
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="text-xs font-black text-white">${{ number_format($invoiced, 0) }}</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <p class="text-xs font-black {{ $balance > 0 ? 'text-amber-400' : 'text-zinc-600' }}">
                                        {{ $balance > 0 ? '$'.number_format($balance, 0) : '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($invoiced > 0)
                                        <div class="flex items-center gap-2 justify-center">
                                            <div class="w-16 h-1.5 bg-black/50 rounded-full overflow-hidden">
                                                <div class="h-full {{ $sb }} rounded-full" style="width: {{ $score }}%"></div>
                                            </div>
                                            <span class="text-[9px] font-black {{ $sc }}">{{ $score }}%</span>
                                        </div>
                                    @else
                                        <span class="text-[9px] text-zinc-700">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-[9px] text-zinc-500 flex items-center gap-1.5">
                                            <i class="fas fa-envelope text-[8px] text-zinc-700"></i>
                                            {{ $customer->email ?? '—' }}
                                        </p>
                                        <p class="text-[9px] text-zinc-600 flex items-center gap-1.5">
                                            <i class="fas fa-phone text-[8px] text-zinc-700"></i>
                                            {{ $customer->phone ?? '—' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('customers.show', $customer) }}"
                                           class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-500 flex items-center justify-center transition-all">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 hover:text-white text-zinc-500 flex items-center justify-center transition-all">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <i class="fas fa-users text-4xl"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.4em]">Sin clientes registrados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-white/5 flex justify-between items-center">
                <span class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">Total: {{ $customers->total() }} clientes</span>
                <div>{{ $customers->links() }}</div>
            </div>
        </div>

    </div>
</x-app-layout>
