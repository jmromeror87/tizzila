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
    <title>Reporte de Carga | TIZZILLA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        
        body { 
            background-color: #030305; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        .receipt-paper {
            background: #0b0b0d;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
            border-radius: 3rem;
        }

        .zigzag {
            height: 12px;
            background: linear-gradient(-45deg, #030305 6px, transparent 0), 
                        linear-gradient(45deg, #030305 6px, transparent 0);
            background-size: 12px 24px;
            width: 100%;
            position: absolute;
            bottom: -1px;
        }

        .text-glow-green { text-shadow: 0 0 15px rgba(34, 197, 94, 0.3); }
    </style>
</head>
<body class="text-white antialiased pb-20">

    <div class="max-w-md mx-auto px-6 pt-12 space-y-10">
        
        {{-- LOGO --}}
        <div class="flex justify-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-egg text-yellow-400 text-2xl"></i>
                <span class="text-white font-black text-xl tracking-tighter uppercase">
                    Tizzilla<span class="text-yellow-400">App</span>
                </span>
            </div>
        </div>

        {{-- ICONO ÉXITO --}}
        <div class="text-center space-y-4">
            <div class="w-24 h-24 mx-auto bg-emerald-500 rounded-[2.5rem] flex items-center justify-center shadow-[0_20px_40px_rgba(16,185,129,0.2)] rotate-3">
                <i class="fas fa-check text-4xl text-black"></i>
            </div>
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tighter">¡LISTO!</h1>
                <p class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.4em] mt-2">Reporte Transmitido con Éxito</p>
            </div>
        </div>

        {{-- RECIBO DIGITAL --}}
        <div class="receipt-paper shadow-2xl overflow-hidden pb-8">
            <div class="p-8 space-y-8">
                
                <div class="text-center border-b border-white/5 pb-8">
                    <p class="text-[9px] text-zinc-600 font-black uppercase tracking-[0.3em] mb-2">Certificado para:</p>
                    <p class="text-2xl font-black uppercase text-white tracking-tight">{{ $stop->customer->name }}</p>
                    <div class="inline-block px-4 py-1 bg-zinc-900 rounded-full mt-3">
                        <p class="text-[9px] text-zinc-400 font-bold uppercase tracking-widest">{{ now()->translatedFormat('d M Y • h:i A') }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    {{-- VIVOS --}}
                    <div class="flex items-center justify-between bg-emerald-500/5 border border-emerald-500/10 p-6 rounded-[2.2rem]">
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Pollitos Vivos</p>
                        <div class="flex items-center gap-3">
                            <span class="text-4xl font-black text-white leading-none text-glow-green">{{ number_format($stop->confirmation->received_quantity) }}</span>
                            <span class="text-2xl">🐥</span>
                        </div>
                    </div>

                    {{-- MUERTOS --}}
                    <div class="flex items-center justify-between bg-red-500/5 border border-red-500/10 p-6 rounded-[2.2rem]">
                        <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">Bajas</p>
                        <div class="flex items-center gap-3">
                            <span class="text-4xl font-black text-white leading-none">{{ number_format($stop->confirmation->dead_quantity) }}</span>
                            <span class="text-2xl opacity-40 filter grayscale">🐥</span>
                        </div>
                    </div>
                </div>

                {{-- EVIDENCIAS --}}
                @if($stop->confirmation->evidences->count())
                <div class="space-y-4 pt-4 border-t border-white/5">
                    <p class="text-[9px] font-black text-zinc-700 uppercase tracking-[0.3em] text-center italic">Archivo Fotográfico</p>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($stop->confirmation->evidences as $evidence)
                            <div class="aspect-square rounded-[1.5rem] overflow-hidden border border-white/5 shadow-inner">
                                <img src="{{ Storage::url($evidence->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- FIRMA --}}
                @if($stop->confirmation->signature_path)
                <div class="pt-8 border-t border-white/5 text-center">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em] mb-4">Firma Digital del Cliente</p>
                    <div class="bg-white/95 rounded-[2rem] p-6 shadow-2xl">
                        <img src="{{ Storage::url($stop->confirmation->signature_path) }}" class="h-24 mx-auto object-contain filter contrast-125">
                    </div>
                </div>
                @endif
            </div>
            {{-- EL ZIGZAG DE RECIBO --}}
            <div class="zigzag"></div>
        </div>

        {{-- ACCIONES --}}
        <div class="space-y-4">
           @php
                $mensajeWA = "✅ *CONFIRMACIÓN DE CARGA - TIZZILLA*\n\n"
                           . "👤 *Cliente:* " . $stop->customer->name . "\n"
                           . "🐥 *Vivos:* " . number_format($stop->confirmation->received_quantity) . "\n"
                           . "💀 *Bajas:* " . number_format($stop->confirmation->dead_quantity) . "\n"
                           . "📅 *Fecha:* " . now()->format('d/m/Y H:i') . "\n\n"
                           . "_Reporte generado digitalmente._";
            @endphp
            
            <a href="https://wa.me/573132106246?text={{ urlencode($mensajeWA) }}" 
               class="flex items-center justify-center w-full bg-[#25D366] text-black py-7 rounded-[2.5rem] font-black uppercase text-[10px] tracking-[0.25em] shadow-[0_15px_40px_rgba(37,211,102,0.2)] active:scale-95 transition-all">
                <i class="fab fa-whatsapp mr-3 text-xl"></i> Notificar a Central
            </a>

            <button onclick="window.print()" class="w-full bg-zinc-900 border border-white/5 text-zinc-400 py-6 rounded-[2.5rem] font-black uppercase text-[10px] tracking-[0.2em] active:scale-95 transition-all">
                <i class="fas fa-print mr-2 opacity-50"></i> Imprimir Copia Ffísica
            </button>
        </div>

        <div class="text-center pt-6">
            <p class="text-[8px] font-black text-zinc-800 uppercase tracking-[0.5em]">Folio Digital TIZZ-{{ str_pad($stop->confirmation->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

    </div>

</body>
</html>