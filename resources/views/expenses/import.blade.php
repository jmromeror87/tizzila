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
                    <i class="fas fa-info-circle mr-2"></i>Formato del CSV
                </p>
                <a href="{{ route('expenses.import.template') }}"
                   class="h-8 px-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-download text-[9px]"></i> Descargar Plantilla Excel/CSV
                </a>
            </div>
            <div class="bg-black/40 rounded-xl p-4 font-mono text-[10px] text-zinc-400 space-y-1">
                <p class="text-yellow-500">MEDIO DE PAGO, DOC-SOPORTE, FECHA, DETALLE, VALOR</p>
                <p>EFECTIVO, GT-01, 1/6/26, PEAJE MORRISON, 14100</p>
                <p>TRANSFERENCIA, GT-05, 1/8/26, PARQUEADERO SOBRERUEDAS, 160000</p>
            </div>
            <div class="grid grid-cols-3 gap-3 text-[9px] text-zinc-500">
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">MEDIO DE PAGO</p>
                    <p>EFECTIVO</p>
                    <p>TRANSFERENCIA</p>
                    <p>TARJETA</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">FECHA</p>
                    <p>1/6/26 → 6 Ene 2026</p>
                    <p>12/31/26 → 31 Dic 2026</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">VALOR</p>
                    <p>Sin puntos ni $</p>
                    <p>14100 ✓</p>
                    <p>14.100 ✗</p>
                </div>
            </div>
            <p class="text-[9px] text-zinc-500">La categoría se detecta automáticamente. Puedes corregirla en la vista previa antes de importar.</p>
        </div>

        {{-- Formulario subida --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <form method="POST" action="{{ route('expenses.import.preview') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-2">Selecciona el archivo CSV</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                           class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-yellow-500/10 file:text-yellow-400">
                </div>
                <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-eye"></i> Vista Previa antes de Importar
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
