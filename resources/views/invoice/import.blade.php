<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.index') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Gestión de Ventas</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Importar <span class="text-yellow-500">Facturas CSV</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 max-w-2xl mx-auto space-y-5">

        {{-- Instrucciones --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-black uppercase tracking-widest text-yellow-400">
                    <i class="fas fa-info-circle mr-2"></i>Formato del CSV
                </p>
                <a href="{{ route('invoices.import.template') }}"
                   class="h-8 px-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-download text-[9px]"></i> Descargar Plantilla
                </a>
            </div>
            <div class="bg-black/40 rounded-xl p-4 font-mono text-[10px] text-zinc-400 space-y-1">
                <p class="text-yellow-500">NUMERO, FECHA, NIT_CLIENTE, DESCRIPCION, VALOR</p>
                <p>006826, 2026-01-09, 88032951, POLLITO BB, 11550000</p>
                <p>006827, 2026-01-09, 77023217, POLLITO BB, 6600000</p>
            </div>
            <div class="grid grid-cols-3 gap-3 text-[9px] text-zinc-500">
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">NUMERO</p>
                    <p>Número exacto del sistema origen</p>
                    <p class="text-yellow-400">006826 ✓</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">FECHA</p>
                    <p>YYYY-MM-DD</p>
                    <p class="text-yellow-400">2026-01-09 ✓</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">VALOR</p>
                    <p>Sin puntos ni $</p>
                    <p class="text-yellow-400">11550000 ✓</p>
                </div>
            </div>
            <p class="text-[9px] text-zinc-500">
                <i class="fas fa-shield-alt text-yellow-500 mr-1"></i>
                Los números de factura se respetan exactamente. No se generan números automáticos. Las facturas duplicadas se omiten.
            </p>
        </div>

        {{-- Formulario subida --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <form method="POST" action="{{ route('invoices.import.preview') }}" enctype="multipart/form-data" class="space-y-4">
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
