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
    {{-- Tipografías y Estilos --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #050507; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2.5rem;
        }
        .bg-vivos { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); }
        .bg-muertos { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); }
    </style>

    <div class="min-h-screen text-white pb-20 pt-6 px-4">
        <div class="max-w-3xl mx-auto space-y-6">
            
            {{-- ENCABEZADO: TIPO RECIBO --}}
            <div class="flex flex-col gap-2 text-center mb-8">
                <div class="flex justify-center">
                    <span class="px-4 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 text-[10px] font-black uppercase tracking-[0.3em]">
                        Comprobante de Entrega
                    </span>
                </div>
                <h1 class="text-4xl font-black uppercase tracking-tighter  ">Resumen de Carga</h1>
                <p class="text-zinc-500 font-bold text-sm uppercase">{{ $confirmation->stop->customer->name }}</p>
            </div>

            {{-- TARJETA PRINCIPAL: VIVOS Y MUERTOS (DIDÁCTICO) --}}
            <div class="glass-card p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- VIVOS --}}
                    <div class="bg-vivos rounded-3xl p-6 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">Recibidos Vivos</p>
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-6xl font-black text-white  leading-none">{{ number_format($confirmation->received_quantity) }}</span>
                            <span class="text-4xl">🐥</span>
                        </div>
                        <p class="text-[9px] text-emerald-500/60 font-bold mt-2 uppercase">Carga aceptada</p>
                    </div>

                    {{-- MUERTOS --}}
                    <div class="bg-muertos rounded-3xl p-6 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">Reporte de Bajas</p>
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-6xl font-black text-white  leading-none">{{ number_format($confirmation->dead_quantity) }}</span>
                            <span class="text-4xl">🐥</span>
                        </div>
                        <p class="text-[9px] text-red-500/60 font-bold mt-2 uppercase">Pérdida registrada</p>
                    </div>
                </div>

                {{-- INFO DE FECHA Y LUGAR --}}
                <div class="flex flex-wrap justify-between gap-4 border-t border-white/5 pt-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-yellow-500">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase">Fecha y Hora</p>
                            <p class="text-sm font-bold">{{ $confirmation->confirmed_at->format('d/m/Y - h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-blue-500">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase">Punto de Entrega</p>
                            <p class="text-sm font-bold truncate max-w-[150px]">{{ $confirmation->stop->customer_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN DE FOTOS (LO QUE EL CAMPESINO CARGÓ) --}}
            @if($confirmation->evidences->count())
            <div class="space-y-4">
                <h3 class="text-sm font-black uppercase tracking-widest text-zinc-500 ml-4 ">Fotos de la Entrega</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($confirmation->evidences as $evidence)
                        <div class="glass-card p-2 group relative">
                            <img src="{{ Storage::url($evidence->image_path) }}"
                                 class="w-full h-48 object-cover rounded-[1.5rem] shadow-xl group-hover:scale-[1.02] transition-transform">
                            <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md px-2 py-1 rounded-lg border border-white/10">
                                <i class="fas fa-camera text-[10px] text-yellow-500"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- SECCIÓN DE FIRMA (IDENTIDAD) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Firma --}}
                <div class="glass-card p-6 flex flex-col items-center">
                    <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-6 ">Firma de Conformidad</p>
                    @if($confirmation->signature_path)
                        <div class="bg-white rounded-3xl p-4 w-full flex items-center justify-center shadow-2xl">
                            <img src="{{ Storage::url($confirmation->signature_path) }}"
                                 class="max-h-32 object-contain filter contrast-125">
                        </div>
                    @else
                        <div class="h-32 w-full flex items-center justify-center bg-zinc-900/50 rounded-3xl border border-dashed border-white/10">
                            <p class="text-[10px] font-bold text-zinc-700">SIN FIRMA DIGITAL</p>
                        </div>
                    @endif
                    <div class="mt-4 flex items-center gap-2 text-[10px] font-black text-emerald-500 uppercase tracking-tighter">
                        <i class="fas fa-shield-check"></i> Documento Verificado
                    </div>
                </div>

                {{-- Ubicación --}}
                <div class="glass-card p-6 flex flex-col items-center justify-center text-center space-y-4">
                    <i class="fas fa-satellite-dish text-3xl text-zinc-800"></i>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Coordenadas GPS</p>
                        <p class="text-[11px] mono font-bold text-zinc-300 mt-1">
                            {{ $confirmation->latitude }}, {{ $confirmation->longitude }}
                        </p>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $confirmation->latitude }},{{ $confirmation->longitude }}" target="_blank"
                       class="px-6 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-black uppercase transition-all">
                        Ver en el Mapa
                    </a>
                </div>
            </div>

            {{-- NOTAS --}}
            @if($confirmation->notes)
            <div class="glass-card p-8">
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-2">Comentarios Registrados</p>
                <p class="text-zinc-300  text-lg leading-relaxed">
                    "{{ $confirmation->notes }}"
                </p>
            </div>
            @endif

            {{-- BOTÓN DE CIERRE O IMPRESIÓN --}}
            <div class="flex justify-center pt-6">
                <button onclick="window.print()" class="bg-white text-black px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:bg-yellow-500 transition-colors">
                    <i class="fas fa-print mr-2"></i> Imprimir Acta
                </button>
            </div>

        </div>
    </div>
</x-app-layout>