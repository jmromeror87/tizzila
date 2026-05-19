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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido — TizzilaApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#050507] text-white min-h-screen flex items-center justify-center font-sans">
    <div class="text-center px-6 max-w-md">
        <div class="mb-8 relative">
            <div class="text-[120px] font-black text-white/5 leading-none select-none">403</div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="h-20 w-20 rounded-3xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                    <i class="fas fa-lock text-red-400 text-3xl"></i>
                </div>
            </div>
        </div>

        <h1 class="text-2xl font-black uppercase tracking-tighter mb-2">
            Acceso <span class="text-red-400">Restringido</span>
        </h1>
        <p class="text-gray-500 text-sm mb-8">
            Tu rol no tiene permisos para acceder a esta sección.
            Contacta al administrador si crees que esto es un error.
        </p>

        @auth
            <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 mb-8 text-left">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-600 mb-2">Sesión activa</p>
                <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-yellow-500 font-bold mt-0.5">{{ auth()->user()->role?->label ?? 'Sin rol asignado' }}</p>
            </div>
        @endauth

        <div class="flex gap-3 justify-center">
            <a href="{{ url()->previous() }}"
               class="px-6 py-3 rounded-xl border border-white/10 text-gray-400 text-[10px] font-black uppercase tracking-widest hover:bg-white/5 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-6 py-3 rounded-xl bg-yellow-500 text-black text-[10px] font-black uppercase tracking-widest hover:bg-yellow-400 transition-all">
                <i class="fas fa-th-large mr-2"></i>Dashboard
            </a>
        </div>
    </div>
</body>
</html>
