{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Gestión de <span class="text-yellow-500">Conductores</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Flota Logística · Operadores Activos
                </p>
            </div>
            <a href="{{ route('poultry.drivers.create') }}"
               class="h-9 px-5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all active:scale-95 w-fit">
                <i class="fas fa-plus text-xs"></i> Nuevo Conductor
            </a>
        </div>
    </x-slot>

    <div class="py-4">

        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold">
                {{ session('warning') }}
            </div>
        @endif

        {{-- KPIs --}}
        @php
            $activeCount   = $drivers->where('status', 'active')->count();
            $inactiveCount = $drivers->where('status', 'inactive')->count();
            $totalRoutes   = $drivers->sum('routes_count');
        @endphp
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Total</p>
                <p class="text-2xl font-black text-white">{{ $drivers->count() }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Operativos</p>
                <p class="text-2xl font-black text-emerald-500">{{ $activeCount }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Rutas Total</p>
                <p class="text-2xl font-black text-yellow-500">{{ $totalRoutes }}</p>
            </div>
        </div>

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500">Conductor</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500">Contacto</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500">Vehículo</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 text-center">Rutas</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-gray-500 text-center">Estado</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04]">
                        @forelse($drivers as $driver)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-zinc-900 border border-white/5 flex items-center justify-center text-yellow-500 font-black text-base">
                                            {{ substr($driver->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-white uppercase tracking-tight">{{ $driver->full_name }}</p>
                                            <p class="text-[9px] font-bold text-yellow-500/60 uppercase tracking-widest mt-0.5">DRV-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-[10px] font-mono text-gray-400">
                                            <i class="fas fa-phone-alt text-[9px] text-yellow-500/60"></i>
                                            {{ $driver->phone }}
                                        </div>
                                        @if($driver->whatsapp)
                                            <div class="flex items-center gap-2 text-[10px] text-emerald-400/70">
                                                <i class="fab fa-whatsapp text-[9px]"></i>
                                                {{ $driver->whatsapp }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div>
                                        <p class="text-[11px] font-black text-white uppercase tracking-[0.2em]">{{ $driver->truck_plate }}</p>
                                        <p class="text-[9px] text-gray-600 uppercase mt-0.5">{{ $driver->truck_type ?? 'Carga General' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-sm font-black {{ $driver->routes_count > 0 ? 'text-yellow-500' : 'text-gray-700' }}">
                                        {{ $driver->routes_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($driver->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[9px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Operativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-500 text-[9px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('poultry.drivers.edit', $driver) }}"
                                           class="h-8 w-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/5 flex items-center justify-center text-gray-400 hover:text-yellow-500 transition-all">
                                            <i class="fas fa-pencil-alt text-[10px]"></i>
                                        </a>
                                        <form method="POST" action="{{ route('poultry.drivers.destroy', $driver) }}" class="inline"
                                              onsubmit="return confirm('¿Desactivar a {{ $driver->full_name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="h-8 w-8 rounded-lg bg-white/5 hover:bg-rose-500/20 border border-white/5 flex items-center justify-center text-gray-400 hover:text-rose-500 transition-all">
                                                <i class="fas fa-power-off text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <i class="fas fa-users-slash text-3xl text-gray-800 block mb-3"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin conductores registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
