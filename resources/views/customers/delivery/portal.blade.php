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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>TIZZILLA | Seguimiento en Vivo</title>
    
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        
        :root {
            --apple-blur: blur(25px);
            --border-white: rgba(255, 255, 255, 0.08);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #000; 
            color: #fff; 
            margin: 0; 
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }

        /* --- MAPA MINIMALISTA --- */
        #map { 
            height: 48vh; 
            width: 100%; 
            mask-image: linear-gradient(to bottom, black 75%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 75%, transparent 100%);
        }

        .main-content {
            margin-top: -12vh;
            padding: 0 1rem 3rem;
            position: relative;
            z-index: 100;
        }

        /* --- ESTÉTICA GLASSMORPHISM --- */
        .card-tizzilla {
            background: rgba(18, 18, 20, 0.8);
            backdrop-filter: var(--apple-blur);
            -webkit-backdrop-filter: var(--apple-blur);
            border: 1px solid var(--border-white);
            border-radius: 2.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* --- ELIMINACIÓN DE SPINNERS (FLECHAS) --- */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* --- INPUTS ESTILIZADOS --- */
        .counter-input {
            background: transparent;
            font-size: 4.5rem;
            font-weight: 900;
            text-align: center;
            color: #ef4444; 
            width: 100%;
            outline: none;
            border: none;
            line-height: 1;
        }

        #received_quantity {
            background: transparent;
            text-align: right;
            font-size: 2.25rem;
            font-weight: 900;
            color: #fff;
            width: 120px;
            outline: none;
            border: none;
        }

        /* --- MARCADORES --- */
        .marker-truck {
            background: #facc15;
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 25px rgba(250, 204, 21, 0.5);
            color: #000; font-size: 24px;
            border: 2px solid rgba(0,0,0,0.1);
        }

        .marker-user {
            width: 24px; height: 24px;
            background: #3b82f6;
            border: 3.5px solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
        }

        canvas { 
            background-color: #fff; 
            border-radius: 2rem; 
            width: 100%; 
            height: 180px; 
            cursor: crosshair;
            touch-action: none;
        }
    </style>
</head>
<body>

    <div id="map"></div>

    <div class="main-content max-w-lg mx-auto">

        
        
        <form method="POST" action="{{ route('customer.delivery.confirm', $stop->public_token) }}" enctype="multipart/form-data" id="confirm-form" class="space-y-4">
            @csrf
            <input type="hidden" id="base_quantity" value="{{ $stop->chicks_quantity }}">

            <div class="card-tizzilla p-8">
                <div class="text-center mb-6">
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">{{ $stop->customer->name }}</h2>
                    <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mt-1">Orden #{{ $stop->id }}</p>
                </div>

                <div class="flex items-center justify-between gap-2 mb-8">
                    <button type="button" onclick="stepDead(-1)" class="w-16 h-16 rounded-3xl bg-zinc-900 flex items-center justify-center text-2xl border border-white/5 active:scale-90 transition-all">
                        <i class="fa-solid fa-minus text-zinc-400"></i>
                    </button>
                    
                    <div class="flex flex-col items-center flex-1">
                        <input type="number" name="dead_quantity" id="dead_quantity" value="0" 
                               inputmode="numeric" oninput="updateReceived()"
                               class="counter-input">
                        <span class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em]">Muertos</span>
                    </div>

                    <button type="button" onclick="stepDead(1)" class="w-16 h-16 rounded-3xl bg-red-600 flex items-center justify-center text-2xl shadow-lg shadow-red-900/30 active:scale-90 transition-all">
                        <i class="fa-solid fa-plus text-white"></i>
                    </button>
                </div>

                <div class="flex justify-between items-center bg-green-500/10 p-6 rounded-[2rem] border border-green-500/20">
                    <div>
                        <p class="text-[10px] font-black text-green-500 uppercase tracking-wider">Recibidos Vivos</p>
                        <p class="text-[11px] text-zinc-400 font-bold">Total certificado</p>
                    </div>
                    <div class="flex items-center">
                        <input type="number" name="received_quantity" id="received_quantity" readonly>
                        <span class="text-2xl ml-1">🐥</span>
                    </div>
                </div>
            </div>

            <div class="card-tizzilla p-6">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-camera text-yellow-500 text-sm"></i>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest">Evidencia de Carga</p>
                </div>
                
                <label class="relative block w-full bg-black/40 border-2 border-dashed border-zinc-800 rounded-[2rem] p-8 text-center active:bg-zinc-800/50 transition-all">
                    <input type="file" name="evidences[]" id="foto-input" multiple accept="image/*" capture="environment" class="absolute inset-0 opacity-0 z-20">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-zinc-700 mb-2"></i>
                    <p class="text-[10px] font-bold text-zinc-500 uppercase">Toca para tomar fotos</p>
                </label>
                <div id="previsualizacion" class="grid grid-cols-4 gap-2 mt-4 px-1"></div>
            </div>

            <div class="card-tizzilla p-6">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <i class="fa-solid fa-pen-nib text-blue-500 text-sm"></i>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest">Firma de Conformidad</p>
                </div>
                <canvas id="signature-pad"></canvas>
                <button type="button" onclick="clearSignature()" class="w-full mt-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest">Limpiar y reintentar</button>
                <input type="hidden" name="signature" id="signature-input">
            </div>

            <button type="submit" onclick="prepareSignature(event)" class="w-full h-16 bg-yellow-500 rounded-2xl text-black font-black text-lg active:scale-95 transition-all uppercase tracking-widest">
                Finalizar Reporte
            </button>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // --- 1. CONFIGURACIÓN DEL MAPA ---
    mapboxgl.accessToken = '{{ config("services.mapbox.token") }}';
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/navigation-night-v1',
        center: [{{ $stop->longitude ?? -73.34 }}, {{ $stop->latitude ?? 8.24 }}],
        zoom: 15.5,
        pitch: 45,
        attributionControl: false
    });

    // Marcador del Camión (Ubicación Driver)
    const truckEl = document.createElement('div');
    truckEl.className = 'marker-truck';
    truckEl.innerHTML = '<i class="fa-solid fa-truck"></i>';
    const truckMarker = new mapboxgl.Marker(truckEl)
        .setLngLat([{{ $stop->route->driver->last_lng ?? -73.341 }}, {{ $stop->route->driver->last_lat ?? 8.241 }}])
        .addTo(map);

    // Marcador del Usuario (Punto Azul GPS)
    const userEl = document.createElement('div');
    userEl.className = 'marker-user';
    const userMarker = new mapboxgl.Marker(userEl)
        .setLngLat([{{ $stop->longitude ?? -73.34 }}, {{ $stop->latitude ?? 8.24 }}])
        .addTo(map);

    // --- 2. LÓGICA DE CANTIDADES ---
    const baseQty = parseInt(document.getElementById('base_quantity').value);
    const deadInp = document.getElementById('dead_quantity');
    const receivedInp = document.getElementById('received_quantity');

    function updateReceived() {
        let dead = parseInt(deadInp.value);
        if (isNaN(dead)) dead = 0;
        // Evitar que pongan más muertos de los que existen
        if (dead > baseQty) {
            dead = baseQty;
            deadInp.value = baseQty;
        }
        receivedInp.value = baseQty - dead;
    }

    function stepDead(val) {
        let current = parseInt(deadInp.value) || 0;
        deadInp.value = Math.max(0, current + val);
        updateReceived();
    }
    
    updateReceived();

    // --- 3. PREVISUALIZACIÓN DE FOTOS ---
    document.getElementById('foto-input').addEventListener('change', function() {
        const prev = document.getElementById('previsualizacion');
        prev.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const d = document.createElement('div');
                d.className = "aspect-square rounded-2xl bg-cover bg-center border border-white/10 shadow-lg animate-pulse";
                d.style.backgroundImage = `url(${e.target.result})`;
                prev.appendChild(d);
                setTimeout(() => d.classList.remove('animate-pulse'), 500);
            }
            reader.readAsDataURL(file);
        });
    });

    // --- 4. FIRMA DIGITAL ---
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, { 
        penColor: '#000',
        minWidth: 2,
        maxWidth: 4
    });

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    function prepareSignature(e) {
        if (signaturePad.isEmpty()) {
            alert("⚠️ Por favor, firme para confirmar la recepción.");
            e.preventDefault();
            return;
        }
        document.getElementById('signature-input').value = signaturePad.toDataURL();
    }
    function clearSignature() { signaturePad.clear(); }

    // --- 5. GPS EN TIEMPO REAL (PUNTO AZUL) ---
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(p => {
            const lat = p.coords.latitude;
            const lng = p.coords.longitude;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            // Actualizar el punto azul en el mapa suavemente
            userMarker.setLngLat([lng, lat]);
        }, (err) => console.error("Error GPS:", err), {
            enableHighAccuracy: true,
            maximumAge: 1000
        });
    }
</script>

</body>
</html>