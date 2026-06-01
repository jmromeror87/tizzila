<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('invoices.index') }}"
               class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Facturación · Importar</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Importar <span class="text-yellow-500">Facturas CSV</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-4 max-w-3xl mx-auto space-y-5">

        {{-- Qué hace --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <p class="text-[9px] font-black uppercase tracking-widest text-yellow-400 mb-3"><i class="fas fa-bolt mr-2"></i>Qué hace esta importación</p>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-black/30 rounded-xl p-3 text-center">
                    <i class="fas fa-file-invoice text-yellow-400 text-lg mb-2 block"></i>
                    <p class="text-[9px] font-black text-white">Crea la factura</p>
                    <p class="text-[8px] text-zinc-500 mt-1">Con el número exacto del sistema</p>
                </div>
                <div class="bg-black/30 rounded-xl p-3 text-center">
                    <i class="fas fa-book text-blue-400 text-lg mb-2 block"></i>
                    <p class="text-[9px] font-black text-white">Asiento contable</p>
                    <p class="text-[8px] text-zinc-500 mt-1">CxC débito · Ventas crédito</p>
                </div>
                <div class="bg-black/30 rounded-xl p-3 text-center">
                    <i class="fas fa-wallet text-purple-400 text-lg mb-2 block"></i>
                    <p class="text-[9px] font-black text-white">Cartera</p>
                    <p class="text-[8px] text-zinc-500 mt-1">Saldo pendiente por cliente</p>
                </div>
            </div>
            <p class="text-[8px] text-zinc-500 mt-3">
                <i class="fas fa-info-circle text-zinc-600 mr-1"></i>
                Solo se importan las filas con <span class="text-emerald-400 font-black">FACTURADO = SI</span>.
                Las informales van al módulo de Ventas.
            </p>
        </div>

        {{-- Formato --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-black uppercase tracking-widest text-yellow-400">
                    <i class="fas fa-table mr-2"></i>Formato — 13 columnas
                </p>
                <a href="{{ route('invoices.import.template') }}"
                   class="h-8 px-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-download text-[9px]"></i> Descargar Plantilla
                </a>
            </div>

            <div class="bg-black/40 rounded-xl p-4 font-mono text-[9px] text-zinc-400 overflow-x-auto">
                <p class="text-yellow-500 mb-1">FECHA, NUMERO_FACTURA, NIT_CLIENTE, NOMBRE_CLIENTE, ZONA, TIPO_PRODUCTO, LINEA, OBSERVACION, CANTIDAD, PRECIO_COMPRA, PRECIO_VENTA, TOTAL_VENTA, FACTURADO</p>
                <p>2026-01-09, <span class="text-yellow-400">FVE6826</span>, 88032951, JUAN CARLOS CAMARGO, PAMPLONA, POLLITO, BROILER, NINGUNA, 3500, 2620, 3300, 11550000, <span class="text-emerald-400">SI</span></p>
                <p>2026-01-16, <span class="text-zinc-600">SIN</span>, 901243904, AGROQUIMICOS SAS, CUCUTA, POLLITO, BROILER, NINGUNA, 7000, 2620, 3300, 23100000, <span class="text-orange-400">NO</span> ← va a Ventas</p>
            </div>

            <div class="grid grid-cols-3 gap-3 text-[9px] text-zinc-500">
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">FECHA</p>
                    <p class="text-yellow-400">2026-01-09 ✓</p>
                    <p>Formato YYYY-MM-DD</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">NUMERO_FACTURA</p>
                    <p>FVE6826 si tiene número</p>
                    <p>SIN si no tiene</p>
                </div>
                <div class="bg-black/20 rounded-lg p-3">
                    <p class="font-black text-zinc-400 mb-1">FACTURADO</p>
                    <p class="text-emerald-400">SI → entra aquí</p>
                    <p class="text-orange-400">NO → módulo Ventas</p>
                </div>
            </div>
        </div>

        {{-- Upload --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <form method="POST" action="{{ route('invoices.import.preview') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-2">Archivo CSV</label>
                    <label class="w-full flex items-center gap-4 px-4 py-3 rounded-xl bg-black/40 border border-white/10 cursor-pointer hover:border-yellow-500/30 transition-all group">
                        <span class="h-8 px-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-[9px] font-black uppercase tracking-widest group-hover:bg-yellow-500/20 transition-all flex items-center gap-2 shrink-0">
                            <i class="fas fa-folder-open text-[9px]"></i> Seleccionar
                        </span>
                        <span class="text-[10px] text-zinc-500" id="file-label">Ningún archivo seleccionado</span>
                        <input type="file" name="csv_file" accept=".csv,.txt" required class="hidden"
                               onchange="document.getElementById('file-label').textContent = this.files[0]?.name ?? 'Ningún archivo seleccionado'">
                    </label>
                </div>
                <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-eye"></i> Vista Previa
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
