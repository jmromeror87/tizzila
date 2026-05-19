{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4 py-2">
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-rose-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-rose-400 border border-rose-500/50 shadow-[0_10px_20px_rgba(244,63,94,0.2)]">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Registrar <span class="text-[#f3c444]">Gasto</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Salida de Recursos & Causación Contable
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('expenses.index') }}"
                   class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all">
                    Cancelar Operación
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-6xl mx-auto space-y-8">

        {{-- ALERTAS DE ERROR --}}
        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/20 p-6 rounded-[2rem]">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-exclamation-triangle text-rose-500 text-xs"></i>
                    <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Errores de Validación Detectados</span>
                </div>
                <ul class="text-[11px] text-zinc-400 font-bold space-y-1 ml-7">
                    @foreach ($errors->all() as $error)
                        <li>&middot; {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" id="expenseForm"
              class="relative bg-[#0a0a0c] rounded-[2.5rem] p-10 border border-white/5 shadow-2xl overflow-hidden">

            @csrf

            <div class="absolute top-0 right-0 w-64 h-64 bg-[#f3c444]/5 blur-[100px] -z-10 rounded-full"></div>

            {{-- INPUTS OCULTOS --}}
            <input type="hidden" name="tax_base" id="tax_base_hidden">
            <input type="hidden" name="iva" id="iva_hidden">
            <input type="hidden" name="retefuente" id="retefuente_hidden">
            <input type="hidden" name="total" id="total_hidden">

            <div class="space-y-10">

                {{-- SECCIÓN 1: IDENTIFICACIÓN --}}
                <div class="grid md:grid-cols-2 gap-10">
                    <div class="group">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] mb-3 block ml-1 group-focus-within:text-[#f3c444] transition-colors">
                            Proveedor de Servicio
                            <span class="text-zinc-700 ml-1">(Opcional)</span>
                        </label>
                        {{-- ✅ FIX 1: removido required — provider_id es nullable --}}
                        <select name="provider_id"
                                class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold px-5 py-4 focus:border-[#f3c444] focus:ring-0 transition-all appearance-none uppercase">
                            <option value="">Sin proveedor</option>
                            @foreach($providers as $p)
                                {{-- ✅ FIX 3: old() para mantener valor al fallar --}}
                                <option value="{{ $p->id }}" {{ old('provider_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->business_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] mb-3 block ml-1 group-focus-within:text-[#f3c444] transition-colors">
                            Categoría de Gasto
                        </label>
                        <select name="category_id" required
                                class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold px-5 py-4 focus:border-[#f3c444] focus:ring-0 transition-all appearance-none uppercase">
                            <option value="">Seleccione categoría...</option>
                            @foreach($categories as $cat)
                                {{-- ✅ FIX 4: null-safe en puc_code --}}
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}{{ $cat->puc_code ? ' (' . $cat->puc_code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- SECCIÓN 2: SOPORTES --}}
                <div class="grid md:grid-cols-3 gap-6 bg-white/[0.02] p-6 rounded-[2rem] border border-white/5">
                    <div>
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-2 block ml-1">Tipo Documento</label>
                        <select name="document_type" class="w-full bg-black border border-white/5 rounded-xl text-[11px] text-zinc-300 font-bold p-3 focus:border-blue-500 transition-all">
                            {{-- ✅ FIX 3: old() en selects --}}
                            <option value="invoice"      {{ old('document_type') == 'invoice'      ? 'selected' : '' }}>Factura de Venta</option>
                            <option value="equivalent"   {{ old('document_type') == 'equivalent'   ? 'selected' : '' }}>Doc. Equivalente</option>
                            <option value="support_doc"  {{ old('document_type') == 'support_doc'  ? 'selected' : '' }}>Doc. Soporte</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-2 block ml-1">Referencia / N°</label>
                        <input type="text" name="document_number" placeholder="Ej: FE-120"
                               value="{{ old('document_number') }}"
                               class="w-full bg-black border border-white/5 rounded-xl text-[11px] text-white font-bold p-3 focus:border-blue-500 transition-all uppercase">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-2 block ml-1">Archivo Digital</label>
                        <input type="file" name="support_document"
                               class="w-full bg-black border border-white/5 rounded-xl text-[10px] text-zinc-500 font-bold p-[7px] file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-[9px] file:font-black file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700 transition-all">
                    </div>
                </div>

                {{-- SECCIÓN 3: CALCULADORA FINANCIERA --}}
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="relative bg-black rounded-2xl p-4 border border-white/5 group hover:border-white/10 transition-all shadow-inner">
                        <label class="text-[9px] font-black text-zinc-600 uppercase tracking-widest block mb-1">Base Gravable</label>
                        <span class="absolute left-4 bottom-4 text-zinc-600 font-black text-xs">$</span>
                        <input type="text" id="tax_base_display" placeholder="0"
                               class="w-full bg-transparent border-none text-white font-black text-lg pl-5 p-0 focus:ring-0">
                    </div>

                    <div class="relative bg-black rounded-2xl p-4 border border-white/5 group hover:border-emerald-500/20 transition-all shadow-inner">
                        <label class="text-[9px] font-black text-emerald-500/60 uppercase tracking-widest block mb-1">IVA (+)</label>
                        <span class="absolute left-4 bottom-4 text-emerald-500/40 font-black text-xs">$</span>
                        <input type="text" id="iva_display" placeholder="0"
                               class="w-full bg-transparent border-none text-emerald-400 font-black text-lg pl-5 p-0 focus:ring-0">
                    </div>

                    <div class="relative bg-black rounded-2xl p-4 border border-white/5 group hover:border-rose-500/20 transition-all shadow-inner">
                        <label class="text-[9px] font-black text-rose-500/60 uppercase tracking-widest block mb-1">Retención (-)</label>
                        <span class="absolute left-4 bottom-4 text-rose-500/40 font-black text-xs">$</span>
                        <input type="text" id="rete_display" placeholder="0"
                               class="w-full bg-transparent border-none text-rose-400 font-black text-lg pl-5 p-0 focus:ring-0">
                    </div>

                    <div class="relative bg-white/[0.02] rounded-2xl p-4 border border-[#f3c444]/20 group transition-all">
                        <label class="text-[9px] font-black text-[#f3c444] uppercase tracking-widest block mb-1">Total Desembolso</label>
                        <span class="absolute left-4 bottom-4 text-[#f3c444]/50 font-black text-xs">$</span>
                        <input type="text" id="total_display" readonly
                               class="w-full bg-transparent border-none text-[#f3c444] font-black text-xl pl-5 p-0 focus:ring-0">
                    </div>
                </div>

                {{-- SECCIÓN 4: PAGO Y DETALLES --}}
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-3 block ml-1">Fecha de Gasto</label>
                        {{-- ✅ FIX 3: old() en fecha --}}
                        <input type="date" name="expense_date" required value="{{ old('expense_date', date('Y-m-d')) }}"
                               class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold px-5 py-4 focus:border-blue-500 transition-all">
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-3 block ml-1">Medio de Pago</label>
                        {{-- ✅ FIX 2: corregido value="transfer text-emerald-400" → value="transfer" --}}
                        <select name="payment_method" class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold px-5 py-4 focus:border-blue-500 transition-all">
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                            <option value="cash"     {{ old('payment_method') == 'cash'     ? 'selected' : '' }}>Efectivo (Caja Menor)</option>
                            <option value="card"     {{ old('payment_method') == 'card'     ? 'selected' : '' }}>Tarjeta Corporativa</option>
                            <option value="other"    {{ old('payment_method') == 'other'    ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="md:col-span-1 flex items-center pt-6">
                        <div class="bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-2xl flex items-center gap-3 w-full">
                            <i class="fas fa-microchip text-emerald-500 animate-pulse"></i>
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest leading-tight">Módulo contable listo para auto-asiento</span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-3 block ml-1">Justificación del Gasto</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-black border border-white/10 rounded-[1.5rem] text-white text-xs font-bold p-5 focus:border-[#f3c444] focus:ring-0 transition-all placeholder:text-zinc-700"
                              placeholder="Escriba los detalles del egreso aquí...">{{ old('description') }}</textarea>
                </div>

                {{-- BOTÓN DE GUARDADO --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                            class="group relative px-12 py-5 bg-[#f3c444] text-black text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-[0_20px_40px_rgba(243,196,68,0.2)] flex items-center gap-3">
                        <i class="fas fa-save group-hover:rotate-12 transition-transform"></i>
                        Ejecutar Registro
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
        const base  = document.getElementById('tax_base_display');
        const iva   = document.getElementById('iva_display');
        const rete  = document.getElementById('rete_display');
        const total = document.getElementById('total_display');

        const limpiar = (v) => parseFloat(v.replace(/\./g, '').replace(',', '.')) || 0;
        const format  = (n) => new Intl.NumberFormat('es-CO').format(n);

        const calcular = () => {
            let b = limpiar(base.value);
            let i = limpiar(iva.value);
            let r = limpiar(rete.value);
            let t = b + i - r;

            total.value = format(t);

            document.getElementById('tax_base_hidden').value  = b;
            document.getElementById('iva_hidden').value       = i;
            document.getElementById('retefuente_hidden').value = r;
            document.getElementById('total_hidden').value     = t;

            if (t <= 0) {
                total.parentElement.classList.add('border-rose-500/50');
                total.classList.replace('text-[#f3c444]', 'text-rose-500');
            } else {
                total.parentElement.classList.remove('border-rose-500/50');
                total.classList.replace('text-rose-500', 'text-[#f3c444]');
            }
        };

        [base, iva, rete].forEach(el => {
            el.addEventListener('input', (e) => {
                // ✅ MEJORA: limpiar antes de formatear para no romper con comas
                let raw = e.target.value.replace(/\./g, '').replace(',', '.');
                let num = parseFloat(raw) || 0;
                e.target.value = format(num);
                calcular();
            });
        });

        document.getElementById('expenseForm').addEventListener('submit', (e) => {
            const t = parseFloat(document.getElementById('total_hidden').value);
            if (!t || t <= 0) {
                e.preventDefault();
                alert('ERROR: El valor total del gasto debe ser superior a cero.');
            }
        });
    </script>

    <style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.5;
            cursor: pointer;
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>