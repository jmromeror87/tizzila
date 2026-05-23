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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tizzila App') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#eab308">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Tizzila">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="font-sans antialiased bg-[#070a13] text-white overflow-x-hidden">

    <div class="min-h-screen flex">

        @include('layouts.navigation')

        <div class="flex-1 flex flex-col min-w-0 ml-0 lg:ml-64 transition-all duration-300 ease-in-out"> 
            
            @isset($header)
                <header class="bg-[#070a13]/80 backdrop-blur-md border-b border-white/5 sticky top-0 z-30">
                    <div class="max-w-7xl mx-auto py-4 px-6 sm:px-8 lg:px-10 flex items-center">
                        
                        <div class="w-12 lg:hidden"></div>

                        <div class="text-lg font-bold text-yellow-500 tracking-tight flex-1">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="flex-1 p-4 sm:p-6 lg:p-10 bg-[#070a13]">
                <div class="max-w-7xl mx-auto">
                    {{-- Flash messages globales --}}
                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                             class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-5 py-3 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-circle text-sm"></i>
                                <span class="text-xs font-bold">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false"><i class="fas fa-times text-[10px]"></i></button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             class="mb-4 bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 px-5 py-3 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-sm"></i>
                                <span class="text-xs font-bold">{{ session('warning') }}</span>
                            </div>
                            <button @click="show = false"><i class="fas fa-times text-[10px]"></i></button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                             class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-5 py-3 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-sm"></i>
                                <span class="text-xs font-bold">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false"><i class="fas fa-times text-[10px]"></i></button>
                        </div>
                    @endif
                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            <footer class="border-t border-white/5 py-6 text-center text-[9px] uppercase tracking-[0.3em]">
                <span class="text-yellow-500 font-black">© {{ date('Y') }} Tizzila App · Orquestación Avícola Inteligente · Ing. Jhoan Romero</span>
            </footer>
        </div>

    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Scrollbar estilo CORK (Oscuro y delgado) */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #070a13; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #eab308; }
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; appearance: textfield; }
    </style>

@stack('scripts')

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
    }
</script>
</body>
</html>