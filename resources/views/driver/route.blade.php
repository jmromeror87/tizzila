{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Tizzilla Driver | Real Road Navigation</title>

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap');

        :root {
            --tizzilla-yellow: #facc15;
            --safe-bottom: env(safe-area-inset-bottom);
        }

        body {
            margin: 0;
            padding: 0;
            background: #050507;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            height: 100vh;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
            height: 75%;
            mask-image: linear-gradient(to bottom, black 85%, transparent 100%);
        }

        .tizzilla-panel {
            background: linear-gradient(to bottom, rgba(15, 15, 18, 0.98), #050507);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: calc(1.5rem + var(--safe-bottom));
        }

        .truck-marker {
            width: 48px;
            height: 48px;
            background: var(--tizzilla-yellow);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            font-size: 22px;
            box-shadow: 0 0 25px rgba(250, 204, 21, 0.5);
            border: 2px solid white;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .action-card:active {
            transform: scale(0.95);
            background: rgba(255, 255, 255, 0.1);
        }

        .badge-info {
            background: rgba(250, 204, 21, 0.1);
            border: 1px solid rgba(250, 204, 21, 0.2);
            color: var(--tizzilla-yellow);
        }
    </style>
</head>

<body class="antialiased text-white select-none">

    <div id="map"></div>

    <div class="absolute z-30 top-10 left-4 right-4">
        <div
            class="bg-black/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-4 flex justify-between items-center shadow-2xl">
            <div class="flex items-center gap-3 border-r border-white/10 pr-4">
                <div class="relative">
                    <div class="h-2 w-2 rounded-full bg-green-500 shadow-[0_0_12px_rgba(34,197,94,0.8)] animate-pulse">
                    </div>
                </div>
                <div>
                    <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.15em] leading-none mb-1">
                        Próxima Parada</p>
                    <p class="text-xs font-black text-white uppercase tracking-tight" id="client-name-top">Buscando...
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-end pl-2">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em] leading-none mb-1 text-right">
                    Llegada Estimada</p>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock text-[10px] text-yellow-500"></i>
                    <p id="eta-display" class="text-sm font-black text-white tracking-tighter">--:--</p>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 z-40 tizzilla-panel rounded-t-[2.5rem] px-6 pt-4 shadow-2xl">
        <div class="w-12 h-1 bg-white/20 rounded-full mx-auto mb-6"></div>

        <div class="flex items-start justify-between mb-6">
            <div class="flex gap-4 items-center">
                <div
                    class="h-14 w-14 bg-yellow-500 rounded-2xl flex flex-col items-center justify-center text-black shadow-lg">
                    <i class="fas fa-truck-loading text-xl"></i>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span id="chicks-info" class="text-2xl font-black leading-none">0</span>
                        <span id="bird-type"
                            class="badge-info text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-tight">Cargando...</span>
                    </div>
                    <p id="client-name-bottom"
                        class="text-gray-400 text-xs font-medium mt-1 truncate w-48 uppercase tracking-wide italic"></p>
                </div>
            </div>
            <div class="text-right">
                <p id="stop-distance" class="text-3xl font-black text-yellow-400 leading-none tracking-tighter">--</p>
                <p class="text-[10px] font-bold text-gray-500 uppercase">KM</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-6">
            <button id="recenter" class="action-card h-16">
                <i class="fa-solid fa-crosshairs text-yellow-500 text-lg mb-1"></i>
                <span class="text-[9px] font-bold uppercase text-gray-400">GPS</span>
            </button>
            <button id="btn-waze" class="action-card h-16">
                <i class="fab fa-waze text-[#33ccff] text-xl mb-1"></i>
                <span class="text-[9px] font-bold uppercase text-gray-400">Waze</span>
            </button>
            <button id="btn-wa" onclick="sendWhatsApp()" class="action-card h-16 border-green-500/20">
                <i id="wa-icon" class="fab fa-whatsapp text-green-500 text-xl mb-1"></i>
                <span id="wa-label" class="text-[9px] font-bold uppercase text-green-500">Avisar</span>
                <span id="wa-time" class="text-[8px] text-green-400 hidden"></span>
            </button>
        </div>

        <button onclick="confirmDelivery()"
            class="w-full h-16 bg-yellow-500 rounded-2xl text-black font-black text-lg active:scale-95 transition-all uppercase tracking-widest">
            Entrega Exitosa
        </button>
    </div>

    <script>
        const APP_URL = "{{ url('/') }}";
    const MAPBOX_TOKEN = '{{ config("services.mapbox.token") }}';
    const stopsData = @json($mapStops ?? $route->stops);

    mapboxgl.accessToken = MAPBOX_TOKEN;
    
    let orderedStops = [];
    let driverPosition = null;
    let currentNextStop = null;

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/navigation-night-v1', // Estilo optimizado para navegación vial
        center: [-73.3426, 8.2439],
        zoom: 16,
        pitch: 50,
        antialias: true
    });

    const driverMarker = new mapboxgl.Marker(Object.assign(document.createElement('div'), {
        className: 'truck-marker',
        innerHTML: '<i class="fas fa-truck"></i>'
    })).setLngLat([0,0]).addTo(map);

    map.on('load', async () => {
        // Inicializar fuente y capa de ruta con trazado vial
        map.addSource('route', {
            type: 'geojson',
            data: { type: 'FeatureCollection', features: [] }
        });

        map.addLayer({
            id: 'route-line',
            type: 'line',
            source: 'route',
            layout: { 'line-join': 'round', 'line-cap': 'round' },
            paint: {
                'line-color': '#facc15',
                'line-width': 7,
                'line-opacity': 0.9
            }
        });
        

// Cargar paradas con el nuevo icono de granjita
for (const stop of stopsData) {
    const addr = stop.address || stop.customer_address;
    try {
        const res = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(addr)}.json?access_token=${MAPBOX_TOKEN}&limit=1`);
        const data = await res.json();
        if (data.features?.length > 0) {
            const coords = data.features[0].geometry.coordinates;
            orderedStops.push({ ...stop, coords });
            
            // CREACIÓN DEL ICONO DE GRANJITA
            const el = document.createElement('div');
            el.className = 'customer-marker';
            // Usamos un icono de FontAwesome dentro de un círculo blanco elegante
            el.innerHTML = `
                <div style="
                    background: white; 
                    width: 35px; 
                    height: 35px; 
                    border-radius: 50%; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    border: 2px solid #facc15;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.5);
                    cursor: pointer;">
                    <i class="fa-solid fa-house-chimney-window" style="color: #8b4513; font-size: 16px;"></i>
                </div>
                <div style="
                    width: 0; 
                    height: 0; 
                    border-left: 6px solid transparent;
                    border-right: 6px solid transparent;
                    border-top: 8px solid #facc15;
                    margin: -2px auto 0 auto;">
                </div>
            `;

            // Añadir el marcador al mapa
            new mapboxgl.Marker(el)
                .setLngLat(coords)
                .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML(`<b style="color:black">${stop.customer?.name}</b>`))
                .addTo(map);
        }
    } catch (e) { console.error(e); }
}

        // Cargar puntos de entrega
        for (const stop of stopsData) {
            const addr = stop.address || stop.customer_address;
            try {
                const res = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(addr)}.json?access_token=${MAPBOX_TOKEN}&limit=1`);
                const data = await res.json();
                if (data.features?.length > 0) {
                    const coords = data.features[0].geometry.coordinates;
                    orderedStops.push({ ...stop, coords });  
                }
            } catch (e) { console.error(e); }
        }
        startTracking();
    });

    function startTracking() {
        navigator.geolocation.watchPosition(async (pos) => {
            driverPosition = [pos.coords.longitude, pos.coords.latitude];
            driverMarker.setLngLat(driverPosition);
            
            if (orderedStops.length > 0) {
                // Ordenar por distancia real (hipotenusa básica para priorizar el más cercano)
                orderedStops.sort((a, b) => {
                    const da = Math.hypot(a.coords[0]-driverPosition[0], a.coords[1]-driverPosition[1]);
                    const db = Math.hypot(b.coords[0]-driverPosition[0], b.coords[1]-driverPosition[1]);
                    return da - db;
                });
                currentNextStop = orderedStops[0];
                updateNavigation(currentNextStop);
            }
        }, null, { enableHighAccuracy: true });
    }

    async function updateNavigation(nextStop) {
        // PERFIL DRIVING: Obliga a Mapbox a buscar el camino por las calles, no línea recta
        const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${driverPosition.join(',')};${nextStop.coords.join(',')}?geometries=geojson&overview=full&access_token=${MAPBOX_TOKEN}`;
        
        try {
            const res = await fetch(url);
            const data = await res.json();
            if (!data.routes?.[0]) return;
            
            const route = data.routes[0];

            // Dibujar la geometría real de las calles
            if (map.getSource('route')) {
                map.getSource('route').setData(route.geometry);
            }

            // Actualizar Interfaz
            updateWaButton(nextStop.id);
            document.getElementById('client-name-top').innerText = nextStop.customer?.name || "CLIENTE";
            document.getElementById('chicks-info').innerText = nextStop.chicks_quantity || "0";
            document.getElementById('bird-type').innerText = nextStop.bird_type || "POLLITOS";
            document.getElementById('client-name-bottom').innerText = nextStop.customer?.name || "";
            document.getElementById('stop-distance').innerText = (route.distance / 1000).toFixed(1);

            const eta = new Date();
            eta.setSeconds(eta.getSeconds() + route.duration);
            document.getElementById('eta-display').innerText = eta.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            // Centrar suavemente
            map.easeTo({ center: driverPosition, duration: 1000 });
            
        } catch (e) { console.error("Error de navegación vial:", e); }
    }

    // Track sent state per stop id
    const waSentState = {};

    function updateWaButton(stopId) {
        const btn   = document.getElementById('btn-wa');
        const icon  = document.getElementById('wa-icon');
        const label = document.getElementById('wa-label');
        const time  = document.getElementById('wa-time');

        if (waSentState[stopId]) {
            icon.className  = 'fa-solid fa-check text-green-400 text-xl mb-0.5';
            label.textContent = 'Enviado';
            time.textContent  = waSentState[stopId];
            time.classList.remove('hidden');
            btn.classList.add('border-green-500/40');
        } else {
            icon.className  = 'fab fa-whatsapp text-green-500 text-xl mb-1';
            label.textContent = 'Avisar';
            time.classList.add('hidden');
        }
    }

    async function sendWhatsApp() {
        if (!currentNextStop) return;

        const stopId = currentNextStop.id;
        const btn    = document.getElementById('btn-wa');
        const label  = document.getElementById('wa-label');

        // Show loading
        label.textContent = '...';
        btn.disabled = true;

        try {
            const res  = await fetch(`${APP_URL}/driver/stop/send-wa/${stopId}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.ok) {
                waSentState[stopId] = data.sent_at || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            } else {
                alert(data.error || 'Error al enviar WhatsApp');
            }
        } catch (e) {
            alert('Error de conexión');
        } finally {
            btn.disabled = false;
            updateWaButton(stopId);
        }
    }

    function confirmDelivery() {
        if (confirm("¿Confirmar entrega exitosa?")) {
            orderedStops.shift();
            if (orderedStops.length === 0) location.reload();
        }
    }

    document.getElementById('recenter').onclick = () => map.flyTo({ center: driverPosition, zoom: 17 });
    document.getElementById('btn-waze').onclick = () => {
        if (!currentNextStop) {
            alert('No hay parada activa seleccionada.');
            return;
        }

        const lat  = currentNextStop.coords ? currentNextStop.coords[1] : null;
        const lng  = currentNextStop.coords ? currentNextStop.coords[0] : null;
        const addr = currentNextStop.address || currentNextStop.customer_address || '';

        let wazeUrl;

        if (lat && lng && !(lat === 0 && lng === 0)) {
            // Coordenadas disponibles — navegación exacta
            wazeUrl = `https://waze.com/ul?ll=${lat},${lng}&navigate=yes&zoom=17`;
        } else if (addr) {
            // Sin coordenadas — buscar por dirección
            wazeUrl = `https://waze.com/ul?q=${encodeURIComponent(addr)}&navigate=yes`;
        } else {
            alert('Esta parada no tiene dirección ni coordenadas para navegar.');
            return;
        }

        // Intentar abrir app nativa primero, fallback a web
        const appUrl = wazeUrl.replace('https://waze.com/ul', 'waze://');
        const win = window.open(appUrl, '_blank');

        // Si no abre la app en 1.5s, abrir web
        setTimeout(() => {
            try { window.open(wazeUrl, '_blank'); } catch(e) {}
        }, 1500);
    };
    </script>
</body>

</html>