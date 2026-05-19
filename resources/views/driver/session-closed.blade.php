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
    <title>Ruta Finalizada | TIZZILA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background:#050507; font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-white">

    <div class="text-center px-8">

        <div class="mb-8">
            <div class="h-20 w-20 mx-auto bg-yellow-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-yellow-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-black uppercase tracking-widest mb-4">
            Operación Finalizada
        </h1>

        <p class="text-zinc-400 text-sm mb-8">
            La ruta fue completada correctamente.<br>
            Puede cerrar esta ventana.
        </p>

        <button onclick="window.location.href='about:blank'"
            class="w-full py-4 bg-white text-black rounded-2xl font-black uppercase tracking-widest text-xs active:scale-95 transition-all shadow-xl">
            Cerrar
        </button>

    </div>

</body>
</html>
