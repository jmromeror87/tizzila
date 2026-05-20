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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tizzila') }} - Acceso</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <!-- Fonts: Usamos Inter para mantener la consistencia con el App Layout -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Fondo con efecto de profundidad sutil */
            .bg-auth-pattern {
                background-color: #070a13;
                background-image: radial-gradient(circle at 2px 2px, rgba(234, 179, 8, 0.05) 1px, transparent 0);
                background-size: 40px 40px;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-auth-pattern text-slate-200 h-full">

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">

            <!-- Decoración visual: Brillo de fondo (Glow effect) -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-lg h-64 bg-yellow-500/10 blur-[120px] rounded-full"></div>

            <div class="z-10 transition-transform duration-500 hover:scale-105">
                <a href="/">
                    <!-- Logo con color personalizado para el modo oscuro -->
                    <x-application-logo class="w-24 h-24 fill-current text-yellow-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-[#0d121f] border border-white/5 shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden sm:rounded-2xl z-10">

                <!-- Encabezado opcional dentro de la tarjeta para contexto -->
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-white tracking-tight">Bienvenido a Tizzila</h2>
                    <p class="text-sm text-gray-400 mt-2">Orquestación Avícola Inteligente</p>
                </div>

                {{ $slot }}
            </div>

            <!-- Footer simple para Guest -->
            <div class="mt-8 text-center z-10">
                <p class="text-xs text-gray-500 uppercase tracking-widest">
                    &copy; {{ date('Y') }} {{ config('app.name') }}
                </p>
            </div>

        </div>
    </body>
</html>
