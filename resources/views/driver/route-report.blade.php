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
    <title>Resumen de Ruta | TIZZILLA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #030305; 
            color: #fff; 
            letter-spacing: -0.01em;
        }

        .glass-card { 
            background: rgba(18, 18, 20, 0.6); 
            backdrop-filter: blur(25px); 
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.06); 
        }

        .text-glow-emerald { text-shadow: 0 0 15px rgba(52, 211, 153, 0.3); }
        .text-glow-yellow { text-shadow: 0 0 15px rgba(234, 179, 8, 0.3); }
        
        /* Suavizado de scroll para móviles */
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="antialiased">

<div class="max-w-md mx-auto min-h-screen p-6 pb-20">

     {{-- LOGO --}}
        <div class="flex justify-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-egg text-yellow-400 text-2xl"></i>
                <span class="text-white font-black text-xl tracking-tighter uppercase">
                    Tizzilla<span class="text-yellow-400">App</span>
                </span>
            </div>
        </div>

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-10 mt-6">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[0.5em] text-emerald-500 mb-1 block">
                Operación Exitosa
            </span>
            <h1 class="text-4xl font-black uppercase tracking-tighter leading-none">
                RUTA <span class="text-yellow-500 text-glow-yellow">#{{ $route->id }}</span>
            </h1>
        </div>

        <div class="h-14 w-14 bg-yellow-500 rounded-[1.5rem] flex items-center justify-center shadow-[0_10px_30px_rgba(234,179,8,0.3)]">
            <i class="fa-solid fa-check-double text-black text-2xl"></i>
        </div>
    </div>

    {{-- RESUMEN PRINCIPAL --}}
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="glass-card p-6 rounded-[2.2rem]">
            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Programados</p>
            <p class="text-3xl font-black text-white tracking-tighter">
                {{ number_format($totalScheduled) }}
            </p>
        </div>

        <div class="glass-card p-6 rounded-[2.2rem]">
            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Recibidos</p>
            <p class="text-3xl font-black text-emerald-400 tracking-tighter text-glow-emerald">
                {{ number_format($totalReceived) }}
            </p>
        </div>

       <div class="glass-card p-6 rounded-[2.2rem] col-span-2 flex items-center justify-between">
    <div>
        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Tiempo de Viaje</p>
        <p class="text-2xl font-black text-white tracking-tighter uppercase">
            @php
                $totalMinutes = $duration ?? 0;
                $hours = floor($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
            @endphp

            @if($hours > 0)
                {{ $hours }} <span class="text-sm font-bold text-zinc-600 mr-2">H</span>
            @endif
            {{ $minutes }} <span class="text-sm font-bold text-zinc-600">MIN</span>
        </p>
    </div>
    <i class="fa-solid fa-route text-zinc-800 text-3xl"></i>
</div>
    </div>

    {{-- HORARIOS Y CONDUCTOR --}}
    <div class="glass-card rounded-[2.8rem] p-8 mb-10">
        <div class="flex items-center justify-between mb-8">
            <div class="text-center flex-1 border-r border-white/5">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Salida</p>
                <p class="text-lg font-black text-white">
                    {{ \Carbon\Carbon::parse($route->started_at)->format('h:i') }} <span class="text-[10px] text-zinc-500">{{ \Carbon\Carbon::parse($route->started_at)->format('A') }}</span>
                </p>
            </div>
            <div class="text-center flex-1">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">LLegada</p>
                <p class="text-lg font-black text-white">
                    {{ \Carbon\Carbon::parse($route->finished_at)->format('h:i') }} <span class="text-[10px] text-zinc-500">{{ \Carbon\Carbon::parse($route->finished_at)->format('A') }}</span>
                </p>
            </div>
        </div>

        <div class="bg-white/5 rounded-2xl p-5 flex items-center gap-4 border border-white/5">
            <div class="h-10 w-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                <i class="fa-solid fa-user-check text-yellow-500 text-sm"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Responsable de Ruta</p>
                <p class="text-xs font-black text-white uppercase tracking-tight">
                    {{ $route->driver->full_name ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    {{-- DETALLE DE ENTREGAS --}}
    <div class="flex items-center justify-between mb-6 px-2">
        <h2 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500">
            Manifiesto
        </h2>
        <span class="text-[10px] font-bold text-zinc-700 uppercase tracking-widest">
            {{ count($confirmations) }} Paradas
        </span>
    </div>

    <div class="space-y-4">
        @foreach($confirmations as $conf)
            <div class="glass-card rounded-[2.2rem] p-6 relative overflow-hidden">
                {{-- Badge de numeración discreto --}}
                <div class="absolute top-0 right-0 p-4 opacity-10 font-black text-4xl">
                    {{ $loop->iteration }}
                </div>

                <div class="relative z-10">
                    <p class="text-xs font-black text-yellow-500 uppercase tracking-[0.2em] mb-1">
                        {{ optional($conf->stop->customer)->name ?? 'CLIENTE' }}
                    </p>
                    <p class="text-[10px] text-zinc-500 font-bold mb-6 leading-relaxed">
                        {{ optional($conf->stop)->customer_address }}
                    </p>

                    <div class="grid grid-cols-3 gap-2 border-t border-white/5 pt-5">
                        <div class="text-center border-r border-white/5">
                            <p class="text-[8px] font-black text-zinc-600 uppercase mb-1 tracking-widest">Prog.</p>
                            <p class="text-sm font-black text-white">
                                {{ number_format($conf->scheduled_quantity) }}
                            </p>
                        </div>

                        <div class="text-center border-r border-white/5">
                            <p class="text-[8px] font-black text-zinc-600 uppercase mb-1 tracking-widest">Recib.</p>
                            <p class="text-sm font-black text-emerald-500">
                                {{ number_format($conf->received_quantity) }}
                            </p>
                        </div>

                        <div class="text-center">
                            <p class="text-[8px] font-black text-zinc-600 uppercase mb-1 tracking-widest">Bajas</p>
                            <p class="text-sm font-black {{ $conf->dead_quantity > 0 ? 'text-red-500' : 'text-zinc-800' }}">
                                {{ number_format($conf->dead_quantity) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <span class="text-[8px] font-black text-zinc-700 uppercase tracking-tighter">
                            Check: {{ \Carbon\Carbon::parse($conf->confirmed_at)->format('H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ACCIÓN FINAL --}}
    <div class="mt-12">
        <button onclick="window.location.href='{{ route('driver.session.closed') }}'"
            class="w-full py-7 bg-white text-black rounded-[2.5rem] font-black uppercase tracking-[0.3em] text-[10px] active:scale-[0.97] transition-all shadow-[0_20px_50px_rgba(255,255,255,0.1)]">
            Finalizar Sesión
        </button>
    </div>

</div>

</body>
</html>