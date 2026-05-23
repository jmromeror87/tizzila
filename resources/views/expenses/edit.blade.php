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
                <div class="relative">
                    <div class="absolute -inset-1 bg-[#f3c444]/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-[#f3c444] border border-[#f3c444]/50">
                        <i class="fas fa-edit text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Modificar <span class="text-[#f3c444]">Gasto #{{ $expense->id }}</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1  text-center md:text-left">Ajuste de Registro Contable</p>
                </div>
            </div>

            <a href="{{ route('expenses.show', $expense->id) }}"
               class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                <i class="fas fa-times text-red-500"></i> Cancelar
            </a>
        </div>
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto">

        {{-- 🚨 ALERTAS DE ERROR --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                <ul class="list-disc pl-5 text-xs text-red-400 font-bold uppercase tracking-wide">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('expenses.update', $expense->id) }}" enctype="multipart/form-data"
              class="relative bg-[#0a0a0c] border border-white/5 rounded-[3rem] p-10 shadow-2xl overflow-hidden">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#f3c444]/5 blur-[80px] rounded-full -mr-20 -mt-20"></div>
            
            @csrf
            @method('PUT')

            <div class="relative z-10 space-y-10">

                {{-- 🏦 SECCIÓN: IDENTIFICACIÓN --}}
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2 ">
                            <i class="fas fa-address-card text-[#f3c444]"></i> Proveedor
                        </label>
                        <select name="provider_id" required
                            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-bold focus:border-[#f3c444] focus:ring-0 transition-all uppercase appearance-none cursor-pointer">
                            @foreach($providers as $p)
                                <option value="{{ $p->id }}" {{ old('provider_id', $expense->provider_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->business_name }} — {{ $p->tax_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] flex items-center gap-2 ">
                            <i class="fas fa-tag text-[#f3c444]"></i> Categoría Contable
                        </label>
                        <select name="category_id" required
                            class="w-full bg-black border border-white/10 text-white rounded-2xl px-5 py-4 text-sm font-bold focus:border-[#f3c444] focus:ring-0 transition-all uppercase appearance-none cursor-pointer">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $expense->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->puc_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 📄 SECCIÓN: DOCUMENTACIÓN --}}
                <div class="grid md:grid-cols-3 gap-8 p-8 bg-white/[0.02] rounded-[2rem] border border-white/5">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">Tipo de Doc.</label>
                        <select name="document_type" required
                            class="w-full bg-black border border-white/10 text-white rounded-xl px-4 py-3 text-xs font-black uppercase focus:border-[#f3c444]">
                            <option value="invoice" {{ old('document_type', $expense->document_type) == 'invoice' ? 'selected' : '' }}>Factura Electrónica</option>
                            <option value="equivalent" {{ old('document_type', $expense->document_type) == 'equivalent' ? 'selected' : '' }}>Doc. Equivalente</option>
                            <option value="support_doc" {{ old('document_type', $expense->document_type) == 'support_doc' ? 'selected' : '' }}>Doc. Soporte</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">Nro Documento</label>
                        <input type="text" name="document_number" value="{{ old('document_number', $expense->document_number) }}"
                            class="w-full bg-black border border-white/10 text-white rounded-xl px-4 py-3 text-sm font-black uppercase focus:border-[#f3c444]">
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">Soporte Digital</label>
                        <div class="relative group">
                            <input type="file" name="support_document" id="fileInput"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            <div class="w-full bg-black border border-dashed border-white/20 text-zinc-500 rounded-xl px-4 py-2.5 text-[10px] font-black uppercase flex items-center justify-center gap-2 group-hover:border-[#f3c444]/50 group-hover:text-white transition-all">
                                <i class="fas fa-sync-alt"></i> 
                                <span id="fileNameDisplay">Reemplazar Archivo</span>
                            </div>
                        </div>
                        @if($expense->support_document)
                            <p class="text-[9px] text-[#f3c444] font-black uppercase tracking-tighter truncate">Actual: {{ basename($expense->support_document) }}</p>
                        @endif
                    </div>
                </div>

                {{-- 💰 SECCIÓN: CALCULADORA (VIVA) --}}
                <div class="bg-black rounded-[2rem] p-10 border-l-4 border-[#f3c444] shadow-inner">
                    <div class="grid md:grid-cols-4 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">Base Gravable</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-600 font-black">$</span>
                                <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="tax_base" id="tax_base" required value="{{ old('tax_base', $expense->tax_base) }}"
                                    class="w-full bg-transparent border-b-2 border-white/10 text-white pl-8 pr-4 py-3 text-xl font-black focus:border-[#f3c444] focus:ring-0 transition-all">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">IVA (+)</label>
                            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="iva" id="iva" value="{{ old('iva', $expense->iva) }}"
                                class="w-full bg-transparent border-b-2 border-white/10 text-emerald-500 px-4 py-3 text-xl font-black focus:border-emerald-500">
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">Retención (-)</label>
                            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="retefuente" id="retefuente" value="{{ old('retefuente', $expense->retefuente) }}"
                                class="w-full bg-transparent border-b-2 border-white/10 text-red-500 px-4 py-3 text-xl font-black focus:border-red-500">
                        </div>

                        <div class="space-y-4 bg-white/5 rounded-2xl p-4 border border-white/5">
                            <label class="text-[10px] font-black text-[#f3c444] uppercase tracking-widest ">Total Neto</label>
                            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="total" id="total_display" readonly value="{{ old('total', $expense->total) }}"
                                class="w-full bg-transparent border-none text-white px-0 py-2 text-2xl font-black focus:ring-0">
                        </div>
                    </div>
                </div>

                {{-- ⚙️ SECCIÓN: LOGÍSTICA --}}
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest flex items-center gap-2 ">
                            <i class="fas fa-calendar-day"></i> Fecha del Gasto
                        </label>
                        <input type="date" name="expense_date" required value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                            class="w-full bg-black border border-white/10 text-white rounded-xl px-5 py-4 text-sm font-black focus:border-[#f3c444]">
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest flex items-center gap-2 ">
                            <i class="fas fa-credit-card"></i> Método de Pago
                        </label>
                        <select name="payment_method"
                            class="w-full bg-black border border-white/10 text-white rounded-xl px-5 py-4 text-sm font-black uppercase focus:border-[#f3c444]">
                            <option value="transfer" {{ old('payment_method', $expense->payment_method) == 'transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                            <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Efectivo de Caja</option>
                            <option value="card" {{ old('payment_method', $expense->payment_method) == 'card' ? 'selected' : '' }}>Tarjeta Corporativa</option>
                            <option value="other" {{ old('payment_method', $expense->payment_method) == 'other' ? 'selected' : '' }}>Otros / Cruce</option>
                        </select>
                    </div>
                </div>

                {{-- 📝 NOTAS --}}
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ">Justificación / Descripción</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-black border border-white/10 text-white rounded-[1.5rem] px-6 py-4 text-sm focus:border-[#f3c444] placeholder:text-zinc-800">{{ old('description', $expense->description) }}</textarea>
                </div>

                {{-- BOTONERA --}}
                <div class="pt-6 border-t border-white/5 flex justify-end items-center gap-6">
                    <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest hidden md:block ">Última actualización: {{ $expense->updated_at->diffForHumans() }}</span>
                    <button type="submit"
                        class="px-10 py-5 bg-[#f3c444] text-black text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-[#f3c444]/90 hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-[#f3c444]/10 flex items-center gap-3">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const base = document.getElementById('tax_base');
            const iva = document.getElementById('iva');
            const rete = document.getElementById('retefuente');
            const totalDisplay = document.getElementById('total_display');
            const fileInput = document.getElementById('fileInput');
            const fileNameDisplay = document.getElementById('fileNameDisplay');

            const calcularTotal = () => {
                const b = parseFloat(base.value) || 0;
                const i = parseFloat(iva.value) || 0;
                const r = parseFloat(rete.value) || 0;
                totalDisplay.value = (b + i - r).toFixed(2);
            };

            [base, iva, rete].forEach(el => el.addEventListener('input', calcularTotal));

            fileInput.addEventListener('change', (e) => {
                if(e.target.files.length > 0) {
                    fileNameDisplay.innerText = e.target.files[0].name;
                    fileNameDisplay.classList.add('text-[#f3c444]');
                }
            });
        });
    </script>
</x-app-layout>