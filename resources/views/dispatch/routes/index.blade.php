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
    {{-- Header de la página (Se inyecta en el slot $header del layout) --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-white tracking-tighter uppercase">
                    Centro de <span class="text-yellow-500">Rutas</span>
                </h2>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.3em] font-bold mt-1">
                    Logística & Despachos en Tiempo Real
                </p>
            </div>
            
            <form action="{{ route('dispatch.routes.generate') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <button type="submit" :disabled="loading"
                    class="group bg-yellow-500 hover:bg-yellow-400 text-black px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-[0_10px_20px_rgba(234,179,8,0.2)] active:scale-95 flex items-center gap-3">
                    <i class="fas fa-sync-alt" :class="loading ? 'animate-spin' : 'group-hover:rotate-180 transition-transform'"></i>
                    <span x-text="loading ? 'Optimizando...' : 'Recalcular Operación'"></span>
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-2 relative" x-data="{ loading: false }">
        
        {{-- KPIs Estilo Dashboard Pro --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
                $stats = [
                    ['label' => 'Rutas Activas', 'val' => $routes->count(), 'icon' => 'fa-truck', 'color' => 'text-white', 'bg' => 'bg-blue-500/10'],
                    ['label' => 'Puntos Entrega', 'val' => $routes->sum('total_customers'), 'icon' => 'fa-map-pin', 'color' => 'text-white', 'bg' => 'bg-purple-500/10'],
                    ['label' => 'Total Pollitos', 'val' => number_format($routes->sum('total_chicks')), 'icon' => 'fa-egg', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-500/10'],
                    ['label' => 'Entregas Completas', 'val' => $routes->sum(fn($r) => $r->stops->where('delivery_status','delivered')->count()) . ' / ' . $routes->sum(fn($r) => $r->stops->count()), 'icon' => 'fa-check-circle', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-500/10']
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5 shadow-sm hover:border-yellow-500/30 transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-black {{ $stat['color'] }} tracking-tighter">{{ $stat['val'] }}</p>
                    </div>
                    <div class="{{ $stat['bg'] }} p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i class="fas {{ $stat['icon'] }} {{ $stat['color'] }} text-sm"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Contenedor de Tabla --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Monitor de Despachos</h3>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-1 rounded">LIVE</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-widest text-gray-500 border-b border-white/5">
                            <th class="px-6 py-4 font-black">Operador / Vehículo</th>
                            <th class="px-6 py-4 font-black text-center">H. Salida</th>
                            <th class="px-6 py-4 font-black">Carga Total</th>
                            <th class="px-6 py-4 font-black">Progreso Logístico</th>
                            <th class="px-6 py-4 font-black text-center">Estado</th>
                            <th class="px-6 py-4 font-black text-right">Mando</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($routes as $route)
                            @php
                                $totalStops = $route->stops->count();
                                $completed  = $route->stops->where('delivery_status', 'delivered')->count();
                                $percent    = $totalStops > 0 ? intval(($completed / $totalStops) * 100) : 0;

                                $statusStyle = match($route->status) {
                                    'planned' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'in_progress' => 'bg-blue-500/10 text-blue-500 border-blue-500/20 animate-pulse',
                                    'finished' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    default => 'bg-zinc-500/10 text-zinc-500 border-zinc-500/20',
                                };
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                       <div class="h-12 w-12 rounded-2xl bg-zinc-900 border border-white/5 flex items-center justify-center text-yellow-500">
                                                <i class="fas fa-user-circle text-2xl"></i>
                                            </div>
                                        <div>
                                            <p class="text-xs font-bold text-white uppercase tracking-tight group-hover:text-yellow-500 transition-colors">{{ $route->driver->full_name }}</p>
                                            <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Placa: {{ $route->driver->truck_plate }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-[11px] font-black text-gray-300 bg-black/40 px-2 py-1 rounded border border-white/5">
                                        {{ \Carbon\Carbon::parse($route->departure_time)->format('h:i A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-white">{{ number_format($route->total_chicks) }}</span>
                                        <span class="text-[9px] font-bold text-gray-600 uppercase tracking-tighter">Pollitos</span>
                                        @if($route->status === 'finished' && $route->started_at && $route->finished_at)
                                            @php $mins = \Carbon\Carbon::parse($route->started_at)->diffInMinutes($route->finished_at); @endphp
                                            <span class="text-[9px] font-bold text-emerald-500/70 uppercase tracking-tighter mt-0.5">
                                                ⏱ {{ intdiv($mins, 60) }}h {{ $mins % 60 }}m
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="w-full max-w-[140px]">
                                        <div class="flex justify-between mb-1 items-end">
                                            <span class="text-[9px] font-black text-gray-500 uppercase">{{ $completed }}/{{ $totalStops }} Visitas</span>
                                            <span class="text-[10px] font-black text-yellow-500">{{ $percent }}%</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-black/50 rounded-full overflow-hidden border border-white/5">
                                            <div class="h-full bg-yellow-500 rounded-full shadow-[0_0_8px_rgba(234,179,8,0.4)]" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $statusStyle }}">
                                        {{ $route->status == 'in_progress' ? 'En Ruta' : ($route->status == 'planned' ? 'Standby' : 'Completado') }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('dispatch.routes.show', $route) }}" 
                                       class="inline-flex items-center justify-center h-9 px-4 rounded-lg bg-[#111827] hover:bg-yellow-500 hover:text-black transition-all border border-white/10 font-black text-[9px] uppercase tracking-widest group/btn">
                                        Detalle <i class="fas fa-arrow-right ml-2 text-[7px] group-hover/btn:translate-x-1 transition-transform"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <i class="fas fa-route text-4xl"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.4em]">Fila de despacho vacía</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>