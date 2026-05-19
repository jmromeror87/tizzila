{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<div class="space-y-8">
    {{-- Encabezado de la Sección --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-yellow-500/10 p-2.5 rounded-xl border border-yellow-500/20">
                <i class="fas fa-building text-yellow-500"></i>
            </div>
            <div>
                <h5 class="text-white font-bold mb-0">Identidad Corporativa</h5>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Datos legales registrados</p>
            </div>
        </div>

        {{-- Badge de Modo Lectura --}}
        <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
            <i class="fas fa-lock text-[8px]"></i> Solo lectura
        </span>
    </div>

    {{-- Formulario Estilizado --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Razón Social -->
        <div class="group">
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 px-1 group-hover:text-yellow-500/70 transition-colors">
                Razón Social
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-id-card text-gray-600 text-xs"></i>
                </div>
                <input type="text"
                    value="{{ $company->legal_name }}"
                    class="w-full bg-[#070a13] border-white/10 text-gray-300 text-sm rounded-xl py-3 pl-10 cursor-not-allowed focus:ring-0 opacity-80"
                    disabled>
            </div>
        </div>

        <!-- Nombre Comercial -->
        <div class="group">
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 px-1 group-hover:text-yellow-500/70 transition-colors">
                Nombre Comercial
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-store text-gray-600 text-xs"></i>
                </div>
                <input type="text"
                    value="{{ $company->trade_name }}"
                    class="w-full bg-[#070a13] border-white/10 text-gray-300 text-sm rounded-xl py-3 pl-10 cursor-not-allowed focus:ring-0 opacity-80"
                    disabled>
            </div>
        </div>

        <!-- Correo Electrónico -->
        <div class="group">
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 px-1 group-hover:text-yellow-500/70 transition-colors">
                Correo Institucional
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-600 text-xs"></i>
                </div>
                <input type="email"
                    value="{{ $company->email }}"
                    class="w-full bg-[#070a13] border-white/10 text-gray-300 text-sm rounded-xl py-3 pl-10 cursor-not-allowed focus:ring-0 opacity-80"
                    disabled>
            </div>
        </div>

        <!-- Teléfono -->
        <div class="group">
            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2 px-1 group-hover:text-yellow-500/70 transition-colors">
                Teléfono de Contacto
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fas fa-phone text-gray-600 text-xs"></i>
                </div>
                <input type="text"
                    value="{{ $company->phone }}"
                    class="w-full bg-[#070a13] border-white/10 text-gray-300 text-sm rounded-xl py-3 pl-10 cursor-not-allowed focus:ring-0 opacity-80"
                    disabled>
            </div>
        </div>
    </div>

    {{-- Footer Info --}}
    <div class="mt-6 flex items-center gap-3 p-4 rounded-xl bg-yellow-500/5 border border-yellow-500/10">
        <i class="fas fa-info-circle text-yellow-500/60"></i>
        <p class="text-xs text-gray-500 leading-relaxed ">
            Para modificar los datos legales de la compañía, por favor contacte al administrador o habilite el <span class="text-yellow-500/80 font-bold uppercase tracking-tighter">Modo Edición</span> desde el panel maestro.
        </p>
    </div>
</div>
