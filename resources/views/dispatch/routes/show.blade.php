{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

    <style>
        .mapboxgl-popup-content {
            background: #0d121f !important;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1rem;
            padding: 0.75rem 1rem;
            color: white;
        }
        .mapboxgl-popup-tip { border-top-color: #0d121f !important; }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dispatch.routes.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Ruta <span class="text-yellow-500">#{{ $route->id }}</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        {{ $route->dispatch_date->translatedFormat('d \d\e F, Y') }}
                    </p>
                </div>
            </div>
            <span @class([
                'px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border w-fit',
                'bg-amber-500/10 border-amber-500/20 text-amber-500'   => $route->status === 'planned',
                'bg-blue-500/10 border-blue-500/20 text-blue-400'      => $route->status === 'in_progress',
                'bg-emerald-500/10 border-emerald-500/20 text-emerald-500' => $route->status === 'finished',
            ])>
                {{ match($route->status) {
                    'planned'     => 'Planeado',
                    'in_progress' => 'En Ruta',
                    'finished'    => 'Finalizado',
                    default       => $route->status,
                } }}
            </span>
        </div>
    </x-slot>

    <div class="py-4 space-y-6">

        {{-- KPIs --}}
        @php
            $totalStops = $route->stops->count();
            $delivered  = $route->stops->where('delivery_status', 'delivered')->count();
            $percent    = $totalStops > 0 ? intval(($delivered / $totalStops) * 100) : 0;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Conductor --}}
            <div class="col-span-2 md:col-span-2 bg-[#0d121f] border border-white/5 rounded-2xl p-4 flex items-center gap-4">
                <div class="h-11 w-11 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500 text-lg">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-0.5">Conductor</p>
                    <p class="text-sm font-black text-white uppercase leading-tight">{{ $route->driver->full_name }}</p>
                    <p class="text-[10px] text-yellow-500 font-bold">{{ $route->driver->truck_plate }}</p>
                </div>
                @if($route->started_at && $route->finished_at)
                    @php $mins = \Carbon\Carbon::parse($route->started_at)->diffInMinutes($route->finished_at); @endphp
                    <div class="ml-auto text-right">
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-0.5">Duración</p>
                        <p class="text-sm font-black text-emerald-500">{{ intdiv($mins,60) }}h {{ $mins%60 }}m</p>
                    </div>
                @endif
            </div>
            {{-- Carga --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Carga</p>
                <p class="text-2xl font-black text-yellow-500">{{ number_format($route->total_chicks) }}</p>
                <p class="text-[8px] text-gray-600 font-bold uppercase">Pollitos</p>
            </div>
            {{-- Entregas --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 text-center">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Entregas</p>
                <p class="text-2xl font-black text-white">{{ $delivered }}<span class="text-gray-600 text-lg">/{{ $totalStops }}</span></p>
                <div class="mt-1 h-1 w-full bg-black/50 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>

        {{-- MAPA --}}
        <div class="relative">
            <div id="map" class="w-full h-[380px] rounded-2xl border border-white/5 shadow-xl bg-zinc-900"></div>
            <div class="absolute top-3 left-3 pointer-events-none">
                <span class="bg-black/70 backdrop-blur text-[9px] font-black px-3 py-1.5 rounded-full border border-white/10 uppercase tracking-widest text-white">
                    Mapa en vivo
                </span>
            </div>
        </div>

        {{-- HOJA DE RUTA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-5 py-3 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Hoja de Ruta</h3>
                <i class="fas fa-route text-yellow-500 text-xs"></i>
            </div>
            <div class="divide-y divide-white/[0.04]">
                @foreach($route->stops->sortBy('stop_order') as $stop)
                    <div class="px-5 py-4 flex items-start gap-4 hover:bg-white/[0.01] transition-colors">
                        <div class="h-8 w-8 rounded-full bg-zinc-800 border border-white/10 flex items-center justify-center text-[11px] font-black text-white shrink-0 mt-0.5">
                            {{ $stop->stop_order }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-white uppercase leading-tight truncate">{{ $stop->customer->name }}</p>
                                    <p class="text-[9px] text-zinc-500 uppercase mt-0.5 truncate italic">{{ $stop->customer_address }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-sm font-black text-yellow-500">{{ number_format($stop->chicks_quantity) }}</p>
                                    <p class="text-[8px] text-zinc-600 font-bold uppercase">Unds</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-white/[0.03]">
                                @if($stop->delivery_status === 'pending')
                                    <span class="text-[9px] font-black uppercase text-amber-500/80 flex items-center gap-1">
                                        <i class="fas fa-clock text-[8px]"></i> Pendiente
                                    </span>
                                    <a href="{{ route('dispatch.stops.confirm', $stop) }}"
                                       class="px-4 py-1.5 bg-yellow-500 hover:bg-yellow-400 text-black text-[10px] font-black uppercase rounded-lg transition-all active:scale-95">
                                        Confirmar
                                    </a>
                                @elseif($stop->delivery_status === 'delivered')
                                    <span class="text-[9px] font-black uppercase text-emerald-500 flex items-center gap-1.5">
                                        <i class="fas fa-check-circle"></i> Entregado
                                    </span>
                                    @if($stop->confirmation)
                                        <a href="{{ route('dispatch.confirmations.show', $stop->confirmation->id) }}"
                                           class="text-[9px] font-black uppercase text-yellow-500 hover:text-yellow-400 transition-colors border-b border-yellow-500/30">
                                            Ver Acta
                                        </a>
                                    @endif
                                @else
                                    <span class="text-[9px] font-black uppercase text-rose-500 flex items-center gap-1">
                                        <i class="fas fa-times-circle text-[8px]"></i> Fallido
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ACCIONES --}}
        @if($route->status === 'planned' || $route->status === 'in_progress')
            <div class="space-y-3">
                @if($route->status === 'planned')
                    <form method="POST" action="{{ route('dispatch.routes.start', $route) }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-3.5 bg-yellow-500 hover:bg-yellow-400 active:scale-95 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] text-black transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-play text-xs"></i> Iniciar Operación
                        </button>
                    </form>
                @elseif($route->status === 'in_progress')
                    <form method="POST" action="{{ route('dispatch.routes.finish', $route) }}"
                          onsubmit="return confirm('¿Finalizar esta ruta?')">
                        @csrf
                        <button type="submit"
                            class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 active:scale-95 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] text-black transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-flag-checkered text-xs"></i> Finalizar Ruta
                        </button>
                    </form>
                @endif

                @if(!$route->driver_notified_at)
                    <form method="POST" action="{{ route('dispatch.routes.notifyDriver', $route) }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-3.5 bg-[#25d366] hover:bg-[#1ebe5a] active:scale-95 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] text-black transition-all flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp text-sm"></i> Notificar al Conductor
                        </button>
                    </form>
                @else
                    <div class="px-4 py-3 bg-emerald-500/5 border border-emerald-500/20 rounded-xl text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-400">
                            <i class="fas fa-check-circle mr-1"></i> Conductor notificado · {{ $route->driver_notified_at->format('d/m H:i') }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('dispatch.routes.notifyDriver', $route) }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/10 active:scale-95 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-redo text-[10px]"></i> Reenviar Notificación
                        </button>
                    </form>
                @endif
            </div>
        @endif

    </div>

    <script>
        const dispatchDate  = "{{ $route->dispatch_date->toDateString() }}";
        mapboxgl.accessToken = '{{ $mapboxToken }}';
        const stops         = @json($mapStops);
        const origin        = "{{ $route->origin_address }}";
        const departureTime = "{{ $route->departure_time }}";

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/dark-v11',
            center: [-73.1198, 7.1254],
            zoom: 5.5,
            pitch: 45,
        });

        async function geocode(address) {
            const res  = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(address)}.json?access_token=${mapboxgl.accessToken}`);
            const data = await res.json();
            return data.features[0].geometry.coordinates;
        }

        function calculateETA(baseDate, baseTime, secondsToAdd) {
            const departure = new Date(`${baseDate}T${baseTime}`);
            const arrival   = new Date(departure.getTime() + secondsToAdd * 1000);
            const diffMs    = arrival - departure;
            const hours     = Math.floor(diffMs / (1000 * 60 * 60));
            const minutes   = Math.floor((diffMs / (1000 * 60)) % 60);
            return {
                time:          arrival.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', hour12: true }),
                durationLabel: `${hours}h ${minutes}m`,
            };
        }

        map.on('load', async () => {
            const coordsArray = [];
            const startPoint  = await geocode(origin);
            coordsArray.push(startPoint);

            const originEl = document.createElement('div');
            originEl.style.cssText = "width:36px;height:36px;background:#eab308;border-radius:50%;display:flex;align-items:center;justify-content:center;color:black;font-size:14px;";
            originEl.innerHTML = '<i class="fas fa-industry"></i>';
            new mapboxgl.Marker(originEl).setLngLat(startPoint).addTo(map);

            const stopsWithCoords = [];
            for (const stop of stops) {
                const coords = await geocode(stop.address);
                coordsArray.push(coords);
                stopsWithCoords.push({ ...stop, coords });
            }

            const waypoints    = coordsArray.map(c => c.join(',')).join(';');
            const directionsUrl = `https://api.mapbox.com/directions/v5/mapbox/driving/${waypoints}?geometries=geojson&access_token=${mapboxgl.accessToken}&overview=full`;

            try {
                const res  = await fetch(directionsUrl);
                const data = await res.json();
                const legs = data.routes[0].legs;
                let accumulated = 0;

                map.addSource('route', {
                    type: 'geojson',
                    data: { type: 'Feature', geometry: { type: 'LineString', coordinates: data.routes[0].geometry.coordinates } },
                });
                map.addLayer({
                    id: 'route-line', type: 'line', source: 'route',
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': '#EAB308', 'line-width': 4, 'line-opacity': 0.85 },
                });

                stopsWithCoords.forEach((stop, i) => {
                    accumulated += legs[i].duration;
                    const eta = calculateETA(dispatchDate, departureTime, accumulated);
                    const el  = document.createElement('div');
                    el.style.cssText = "width:30px;height:30px;background:#10b981;border:3px solid #0d121f;border-radius:8px;color:white;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:11px;cursor:pointer;";
                    el.innerHTML = `<span>${i + 1}</span>`;
                    new mapboxgl.Marker(el)
                        .setLngLat(stop.coords)
                        .setPopup(new mapboxgl.Popup({ offset: 20 }).setHTML(`
                            <p class="text-[11px] font-black text-yellow-500 uppercase mb-1">ETA Estimada</p>
                            <p class="text-sm font-bold">${eta.time}</p>
                            <p class="text-[10px] text-gray-400">${eta.durationLabel} de viaje</p>
                        `))
                        .addTo(map);
                });

                const bounds = new mapboxgl.LngLatBounds(data.routes[0].geometry.coordinates[0], data.routes[0].geometry.coordinates[0]);
                data.routes[0].geometry.coordinates.forEach(c => bounds.extend(c));
                map.fitBounds(bounds, { padding: 60, duration: 1800 });

            } catch (e) { console.error('Map error:', e); }
        });
    </script>
</x-app-layout>
