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
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/[0.03]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.856L.057 23.428a.75.75 0 0 0 .916.916l5.573-1.476A11.953 11.953 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.891 0-3.667-.502-5.198-1.381l-.372-.216-3.856 1.021 1.022-3.735-.232-.382A9.953 9.953 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-white">WhatsApp</h1>
                    <p class="text-xs text-white/40">Conexión del sistema de mensajería</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 py-10">

        {{-- Card principal --}}
        <div class="bg-white/[0.03] border border-white/[0.06] rounded-2xl overflow-hidden">

            {{-- Status bar --}}
            <div id="status-bar" class="px-6 py-4 flex items-center gap-3 border-b border-white/[0.06]">
                <div id="status-dot" class="w-2.5 h-2.5 rounded-full bg-zinc-600 animate-pulse"></div>
                <span id="status-text" class="text-sm text-white/60">Verificando conexión...</span>

                <div class="ml-auto">
                    <button
                        id="btn-disconnect"
                        onclick="disconnectWA()"
                        class="hidden text-xs text-red-400 hover:text-red-300 border border-red-500/30 hover:border-red-400/50 px-3 py-1.5 rounded-lg transition-colors">
                        Cerrar sesión
                    </button>
                </div>
            </div>

            {{-- Área central --}}
            <div class="p-8 flex flex-col items-center gap-6 min-h-[340px] justify-center">

                {{-- Estado: cargando --}}
                <div id="state-loading" class="flex flex-col items-center gap-3 text-center">
                    <div class="w-12 h-12 border-2 border-white/10 border-t-green-400 rounded-full animate-spin"></div>
                    <p class="text-white/40 text-sm">Conectando con el servicio...</p>
                </div>

                {{-- Estado: servicio no disponible --}}
                <div id="state-unavailable" class="hidden flex-col items-center gap-4 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-red-500/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium">Servicio no disponible</p>
                        <p class="text-white/40 text-sm mt-1">El microservicio de WhatsApp no está corriendo.</p>
                    </div>
                    <div class="bg-zinc-900 rounded-xl p-4 text-left w-full max-w-sm">
                        <p class="text-xs text-white/50 mb-2 font-mono">Para iniciarlo:</p>
                        <code class="text-xs text-green-400 font-mono">cd whatsapp-service<br>npm install<br>npm start</code>
                    </div>
                </div>

                {{-- Estado: QR listo --}}
                <div id="state-qr" class="hidden flex-col items-center gap-5 text-center">
                    <div>
                        <p class="text-white font-medium text-lg">Escanea con WhatsApp</p>
                        <p class="text-white/40 text-sm mt-1">Abre WhatsApp → Dispositivos vinculados → Vincular dispositivo</p>
                    </div>
                    <div class="p-3 bg-white rounded-2xl shadow-2xl shadow-green-500/10">
                        <img id="qr-image" src="" alt="QR WhatsApp" class="w-52 h-52 rounded-xl" />
                    </div>
                    <p class="text-white/30 text-xs">El QR se actualiza automáticamente</p>
                </div>

                {{-- Estado: conectado --}}
                <div id="state-connected" class="hidden flex-col items-center gap-4 text-center">
                    <div class="w-20 h-20 rounded-full bg-green-500/10 flex items-center justify-center ring-4 ring-green-500/20">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-xl">WhatsApp Conectado</p>
                        <p class="text-green-400/80 text-sm mt-1">El sistema puede enviar mensajes automáticamente</p>
                    </div>

                    {{-- Módulos activos --}}
                    <div class="grid grid-cols-1 gap-2 w-full max-w-xs mt-2">
                        @foreach([
                            ['Recibo de pago', 'Cartera'],
                            ['Link de entrega al cliente', 'Módulo 8'],
                            ['Ruta al conductor', 'Despacho'],
                        ] as [$label, $module])
                        <div class="flex items-center justify-between px-4 py-2.5 bg-green-500/5 border border-green-500/10 rounded-xl">
                            <span class="text-sm text-white/70">{{ $label }}</span>
                            <span class="text-xs text-green-400 bg-green-500/10 px-2 py-0.5 rounded-full">{{ $module }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        const POLL_INTERVAL_QR          = 3000;
        const POLL_INTERVAL_CONNECTED   = 15000;
        const POLL_INTERVAL_UNAVAILABLE = 8000;

        let pollTimer = null;

        function setState(name) {
            ['loading','unavailable','qr','connected'].forEach(s => {
                const el = document.getElementById('state-' + s);
                if (el) el.classList.toggle('hidden', s !== name);
                if (el) el.classList.toggle('flex', s === name);
            });
        }

        function setStatusBar(text, color) {
            const dot  = document.getElementById('status-dot');
            const span = document.getElementById('status-text');
            dot.className  = `w-2.5 h-2.5 rounded-full ${color}`;
            span.textContent = text;
        }

        async function poll() {
            clearTimeout(pollTimer);
            let nextInterval = POLL_INTERVAL_UNAVAILABLE;

            try {
                const res  = await fetch('{{ route("whatsapp.status") }}');
                const data = await res.json();

                if (data.status === 'service_unavailable') {
                    setState('unavailable');
                    setStatusBar('Servicio no disponible', 'bg-red-500');
                    document.getElementById('btn-disconnect').classList.add('hidden');
                    nextInterval = POLL_INTERVAL_UNAVAILABLE;

                } else if (data.status === 'connected') {
                    setState('connected');
                    setStatusBar('Conectado', 'bg-green-500');
                    document.getElementById('btn-disconnect').classList.remove('hidden');
                    nextInterval = POLL_INTERVAL_CONNECTED;

                } else if (data.status === 'qr_ready' && data.qr) {
                    setState('qr');
                    document.getElementById('qr-image').src = data.qr;
                    setStatusBar('Esperando escaneo del QR', 'bg-yellow-400 animate-pulse');
                    document.getElementById('btn-disconnect').classList.add('hidden');
                    nextInterval = POLL_INTERVAL_QR;

                } else {
                    setState('loading');
                    setStatusBar('Iniciando...', 'bg-zinc-600 animate-pulse');
                    nextInterval = POLL_INTERVAL_QR;
                }

            } catch {
                setState('unavailable');
                setStatusBar('Sin conexión al servicio', 'bg-red-500');
                nextInterval = POLL_INTERVAL_UNAVAILABLE;
            }

            pollTimer = setTimeout(poll, nextInterval);
        }

        async function disconnectWA() {
            if (!confirm('¿Cerrar la sesión de WhatsApp? Tendrás que escanear el QR nuevamente.')) return;
            try {
                await fetch('{{ route("whatsapp.disconnect") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                setState('loading');
                setStatusBar('Cerrando sesión...', 'bg-zinc-600 animate-pulse');
                document.getElementById('btn-disconnect').classList.add('hidden');
                setTimeout(poll, 2000);
            } catch (e) {
                alert('Error al cerrar sesión: ' + e.message);
            }
        }

        // Arrancar polling
        poll();
    </script>
    </script>

</x-app-layout>
