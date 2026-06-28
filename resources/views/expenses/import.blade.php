<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('expenses.index') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Gestión de Gastos</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Importar <span class="text-yellow-500">Gastos CSV</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 max-w-2xl mx-auto space-y-5">

        {{-- Instrucciones + Descarga plantilla --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-black uppercase tracking-widest text-yellow-400">
                    <i class="fas fa-table mr-2"></i>Formato — 4 columnas
                </p>
                <a href="{{ route('expenses.import.template') }}"
                   class="h-8 px-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-download text-[9px]"></i> Descargar Plantilla
                </a>
            </div>
            <div class="bg-black/40 rounded-xl p-4 font-mono text-[9px] text-zinc-400 overflow-x-auto space-y-1">
                <p class="text-yellow-500">FECHA, NIT_TERCERO, TERCERO, DETALLE, VALOR</p>
                <p>2/4/26, <span class="text-orange-400">900123456</span>, <span class="text-blue-400">RESTAURANTE MONTANAS AZULES HI</span>, ALIMENTACION, 38500</p>
                <p>2/5/26, <span class="text-orange-400">901243904</span>, <span class="text-blue-400">SOBRERUEDAS</span>, PARQUEADERO CENTRO, 160000</p>
                <p>2/27/26, <span class="text-orange-400">88032951</span>, <span class="text-blue-400">JOSE LUIS CARVAJALINO</span>, CUENTA COBRO FLETE POLLITO, 400000</p>
            </div>
            <div class="grid grid-cols-3 gap-3 text-[9px] text-zinc-500">
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">NIT_TERCERO</p>
                    <p>NIT o cédula de quien cobra</p>
                    <p class="text-zinc-600 mt-1">Sin dígito de verificación</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">TERCERO</p>
                    <p>Nombre del proveedor o persona</p>
                    <p class="text-zinc-600 mt-1">Ej: ALKOSTO, JOSE LUIS CARVAJALINO</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">VALOR</p>
                    <p class="text-emerald-400">38500 ✓</p>
                    <p class="text-red-400">$38.500 ✗ sin $ ni puntos</p>
                </div>
            </div>
            <p class="text-[9px] text-zinc-500"><i class="fas fa-magic text-yellow-600 mr-1"></i>La categoría se detecta automáticamente por el texto. Puedes corregirla antes de importar.</p>
        </div>

        {{-- Formulario subida --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <form method="POST" action="{{ route('expenses.import.preview') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-2">Selecciona el archivo CSV</label>
                    <label class="w-full flex items-center gap-4 px-4 py-3 rounded-xl bg-black/40 border border-white/10 cursor-pointer hover:border-yellow-500/30 transition-all group">
                        <span class="h-8 px-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-[9px] font-black uppercase tracking-widest group-hover:bg-yellow-500/20 transition-all flex items-center gap-2 shrink-0">
                            <i class="fas fa-folder-open text-[9px]"></i> Seleccionar archivo
                        </span>
                        <span class="text-[10px] text-zinc-500" id="file-label">Ningún archivo seleccionado</span>
                        <input type="file" name="csv_file" accept=".csv,.txt" required class="hidden"
                               onchange="document.getElementById('file-label').textContent = this.files[0]?.name ?? 'Ningún archivo seleccionado'">
                    </label>
                </div>
                <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-eye"></i> Vista Previa antes de Importar
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
