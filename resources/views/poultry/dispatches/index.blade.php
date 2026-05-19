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
                    Historial de <span class="text-yellow-500">Despachos</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Panel de Control · <span class="flex-inline items-center gap-1">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span>En Vivo
                    </span>
                </p>
            </div>
            <a href="{{ route('poultry.orders.index') }}"
               class="h-9 px-5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all active:scale-95 w-fit">
                <i class="fas fa-plus text-xs"></i> Nueva Programación
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- Filtros --}}
        <div class="flex flex-col md:flex-row gap-3">
            <form action="{{ route('poultry.dispatches.index') }}" method="GET" class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar cliente..."
                       class="w-full bg-[#0d121f] border border-white/5 rounded-xl py-2.5 pl-10 pr-4 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-gray-700">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
            </form>

            @php
                $currentStatus = request('status');
                $filters = [
                    null         => ['label' => 'Todos',     'icon' => 'fa-list'],
                    'scheduled'  => ['label' => 'Pendientes','icon' => 'fa-clock'],
                    'en_route'   => ['label' => 'En Ruta',   'icon' => 'fa-truck'],
                    'delivered'  => ['label' => 'Entregados','icon' => 'fa-check-double'],
                ];
            @endphp
            <div class="flex gap-2 overflow-x-auto no-scrollbar">
                @foreach($filters as $key => $filter)
                    <a href="{{ route('poultry.dispatches.index', ['status' => $key, 'search' => request('search')]) }}"
                       class="flex items-center gap-1.5 h-9 px-4 rounded-xl border text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap
                       {{ $currentStatus === $key ? 'bg-yellow-500 text-black border-yellow-500' : 'bg-[#0d121f] border-white/5 text-gray-500 hover:text-white hover:border-white/20' }}">
                        <i class="fas {{ $filter['icon'] }} text-[9px]"></i>
                        {{ $filter['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Lista de despachos --}}
        <div class="space-y-3">
            @forelse($dispatches as $dispatch)
                @php
                    $statusLabel = match($dispatch->status) {
                        'scheduled'  => 'Pendiente',
                        'en_route'   => 'En Viaje',
                        'delivered'  => 'Entregado',
                        default      => ucfirst($dispatch->status),
                    };
                    $statusClass = match($dispatch->status) {
                        'delivered'  => 'text-emerald-500 border-emerald-500/20 bg-emerald-500/5',
                        'en_route'   => 'text-yellow-500 border-yellow-500/20 bg-yellow-500/5',
                        default      => 'text-zinc-400 border-zinc-500/20 bg-zinc-500/5',
                    };
                    $barClass = match($dispatch->status) {
                        'delivered'  => 'bg-emerald-500',
                        'en_route'   => 'bg-yellow-500',
                        default      => 'bg-zinc-600',
                    };
                @endphp
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors relative">
                    {{-- barra lateral de estado --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $barClass }}"></div>

                    <div class="pl-5 pr-5 py-4 space-y-3">
                        {{-- Cabecera --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="bg-black/40 border border-white/5 rounded-lg px-2.5 py-1.5 text-center shrink-0">
                                    <p class="text-[7px] font-black text-gray-600 uppercase leading-none">REF</p>
                                    <p class="text-xs font-black text-white">#{{ $dispatch->order->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-white uppercase tracking-tight leading-tight">{{ $dispatch->order->provider->business_name }}</p>
                                    <p class="text-[9px] font-bold text-yellow-500/70 uppercase mt-0.5">{{ number_format($dispatch->items->sum('quantity')) }} Pollitos</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $statusClass }} shrink-0">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        {{-- Destino --}}
                        <div class="bg-black/20 rounded-xl p-3 border border-white/[0.04]">
                            <p class="text-[8px] font-black text-gray-600 uppercase tracking-widest mb-2">Destino Principal</p>
                            @foreach($dispatch->items->take(1) as $item)
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-circle-dot text-yellow-500 text-[9px] shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black text-gray-200 uppercase truncate">{{ $item->customer?->name ?? '—' }}</p>
                                        <p class="text-[9px] text-gray-600 truncate italic mt-0.5">{{ $item->customer?->address ?? 'Ocaña, Norte de Santander' }}</p>
                                    </div>
                                </div>
                            @endforeach
                            @if($dispatch->items->count() > 1)
                                <p class="mt-2 text-[8px] font-black text-yellow-500/50 uppercase tracking-widest">
                                    +{{ $dispatch->items->count() - 1 }} paradas más en ruta
                                </p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex gap-5">
                                <div>
                                    <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest block">Fecha</span>
                                    <span class="text-[10px] font-black text-white uppercase">{{ $dispatch->dispatch_date->format('d M') }}</span>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest block">Salida</span>
                                    <span class="text-[10px] font-black text-yellow-500 uppercase">
                                        {{ $dispatch->departure_time ? \Carbon\Carbon::parse($dispatch->departure_time)->format('h:i A') : '06:00 AM' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('poultry.dispatches.show', $dispatch) }}"
                               class="h-8 px-4 bg-white/5 hover:bg-yellow-500 hover:text-black border border-white/10 hover:border-yellow-500 text-gray-400 rounded-xl flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest transition-all">
                                Detalles <i class="fas fa-chevron-right text-[8px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center bg-[#0d121f] border border-white/5 rounded-2xl">
                    <i class="fas fa-truck-ramp-box text-4xl text-gray-800 block mb-3"></i>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">No hay despachos para mostrar</p>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($dispatches->hasPages())
            <div class="flex items-center justify-between px-1">
                <a href="{{ $dispatches->previousPageUrl() }}"
                   class="h-9 w-9 rounded-xl bg-[#0d121f] border border-white/5 flex items-center justify-center text-gray-500 transition-all {{ $dispatches->onFirstPage() ? 'opacity-20 pointer-events-none' : 'hover:border-white/20 hover:text-white' }}">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">
                    Página <span class="text-white">{{ $dispatches->currentPage() }}</span> / {{ $dispatches->lastPage() }}
                </span>
                <a href="{{ $dispatches->nextPageUrl() }}"
                   class="h-9 w-9 rounded-xl bg-[#0d121f] border border-white/5 flex items-center justify-center text-gray-500 transition-all {{ !$dispatches->hasMorePages() ? 'opacity-20 pointer-events-none' : 'hover:border-white/20 hover:text-white' }}">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        @endif

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
