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
        <div class="flex items-center justify-between px-4 py-2">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-blue-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-blue-400 border border-blue-500/50 shadow-lg">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Nuevo <span class="text-[#f3c444]">Asiento</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1 ">
                        Protocolo de Registro · Tizzila Engine
                    </p>
                </div>
            </div>
            
            {{-- Indicador de Tiempo Real --}}
            <div class="hidden md:block text-right">
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Periodo Actual</p>
                <p class="text-xs font-black text-white uppercase">{{ now()->translatedFormat('F Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-[1400px] mx-auto">
        <form method="POST" action="{{ route('journal.store') }}" id="journal-form">
            @csrf

            {{-- 📑 CABECERA TÉCNICA --}}
            <div class="bg-[#0a0a0c] rounded-[2.5rem] p-10 border border-white/5 shadow-2xl mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-10 opacity-[0.02] text-white pointer-events-none">
                    <i class="fas fa-microchip text-9xl"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 relative z-10">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] ml-2">Fecha de Operación</label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-zinc-700 text-xs"></i>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required 
                                   class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold pl-12 pr-4 py-4 focus:border-[#f3c444] focus:ring-0 transition-all shadow-inner">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] ml-2">Referencia / Soporte</label>
                        <div class="relative">
                            <i class="fas fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-zinc-700 text-xs"></i>
                            <input type="text" name="reference" placeholder="EJ: F-1020 / BOL-99" required
                                   class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold pl-12 pr-4 py-4 focus:border-[#f3c444] focus:ring-0 transition-all uppercase placeholder:text-zinc-800 shadow-inner">
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] ml-2">Concepto Global</label>
                        <div class="relative">
                            <i class="fas fa-quote-left absolute left-4 top-1/2 -translate-y-1/2 text-zinc-700 text-xs"></i>
                            <input type="text" name="description" placeholder="DESCRIBA LA OPERACIÓN..." required
                                   class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs font-bold pl-12 pr-4 py-4 focus:border-[#f3c444] focus:ring-0 transition-all uppercase placeholder:text-zinc-800 shadow-inner">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 📊 GRILLA CONTABLE --}}
            <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl backdrop-blur-sm">
                <table class="w-full text-left border-collapse" id="lines-table">
                    <thead>
                        <tr class="bg-black/60 border-b border-white/5">
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em]">Cuentas PGC / Auxiliares</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em]">Glosa Individual</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Débito</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Crédito</th>
                            <th class="px-8 py-6 w-20"></th>
                        </tr>
                    </thead>
                    <tbody id="lines-body" class="divide-y divide-white/[0.02]">
                        {{-- La fila inicial se inyecta vía JS o mediante un componente Blade --}}
                    </tbody>
                    <tfoot>
                        <tr class="bg-black/80">
                            <td colspan="2" class="px-8 py-8 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <div class="h-[2px] w-12 bg-[#f3c444]/20"></div>
                                    <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em]">Balance Final</span>
                                </div>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <span id="total-debit" class="text-xl font-black text-white font-mono tracking-tighter">$0.00</span>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <span id="total-credit" class="text-xl font-black text-white font-mono tracking-tighter">$0.00</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- 🛠 CONTROLES DE ACCIÓN --}}
            <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-8 bg-black/20 p-6 rounded-[2rem] border border-white/5">
                <button type="button" onclick="addRow()"
                        class="group px-8 py-4 bg-white/5 border border-white/10 text-zinc-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-white/10 hover:text-white hover:border-blue-500/50 transition-all flex items-center gap-3 active:scale-95">
                    <i class="fas fa-plus text-blue-500 group-hover:rotate-90 transition-transform"></i> Insertar Línea
                </button>

                {{-- Status Dinámico --}}
                <div id="balance-badge" class="flex items-center gap-4 px-8 py-4 rounded-2xl border bg-rose-500/10 border-rose-500/20 text-rose-500 transition-all duration-500">
                    <i class="fas fa-exclamation-triangle animate-pulse text-sm"></i>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Asiento Desbalanceado</span>
                </div>

                <button id="save-btn" disabled 
                        class="px-12 py-5 bg-zinc-800 text-zinc-600 text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl transition-all cursor-not-allowed opacity-50 shadow-2xl">
                    Registrar en Diario <i class="fas fa-shield-halved ml-2 opacity-30"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- 🧱 TEMPLATE DE FILA --}}
    <template id="row-template">
        <tr class="group hover:bg-white/[0.03] transition-all">
            <td class="px-6 py-4">
                <div class="relative group-hover:scale-[1.01] transition-transform">
                    <select name="lines[{idx}][account_id]" class="w-full bg-black/40 border border-white/5 rounded-xl text-white text-[10px] font-bold py-3 pl-4 pr-10 focus:ring-0 focus:border-blue-500 cursor-pointer appearance-none uppercase" required>
                        <option value="">-- SELECCIONAR CUENTA --</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" class="bg-[#0a0a0c] text-zinc-300 font-mono">{{ $account->code }} - {{ $account->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] text-zinc-600 pointer-events-none"></i>
                </div>
            </td>
            <td class="px-6 py-4">
                <input type="text" name="lines[{idx}][description]" placeholder="DETALLE ESPECÍFICO..."
                       class="w-full bg-transparent border-none text-zinc-400 text-[10px] font-bold focus:ring-0 placeholder:text-zinc-800 uppercase">
            </td>
            <td class="px-6 py-4">
                <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="lines[{idx}][debit]" value="0.00"
                       class="w-full bg-black/20 border border-white/5 rounded-xl text-right font-mono font-black text-sm text-emerald-500 focus:ring-1 focus:ring-emerald-500/30 debit-input py-2">
            </td>
            <td class="px-6 py-4">
                <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" step="0.01" name="lines[{idx}][credit]" value="0.00"
                       class="w-full bg-black/20 border border-white/5 rounded-xl text-right font-mono font-black text-sm text-rose-500 focus:ring-1 focus:ring-rose-500/30 credit-input py-2">
            </td>
            <td class="px-6 py-4 text-center">
                <button type="button" onclick="removeRow(this)" class="w-8 h-8 rounded-lg bg-rose-500/5 text-rose-900 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-times text-[10px]"></i>
                </button>
            </td>
        </tr>
    </template>

    <script>
        let index = 0;

        function addRow() {
            const template = document.getElementById('row-template').innerHTML;
            const html = template.replace(/{idx}/g, index);
            document.getElementById('lines-body').insertAdjacentHTML('beforeend', html);
            index++;
            calculateTotals();
        }

        function removeRow(btn) {
            if(document.querySelectorAll('#lines-body tr').length > 1) {
                btn.closest('tr').classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => {
                    btn.closest('tr').remove();
                    calculateTotals();
                }, 200);
            }
        }

        function calculateTotals() {
            let totalDebit = 0;
            let totalCredit = 0;

            document.querySelectorAll('.debit-input').forEach(input => totalDebit += parseFloat(input.value || 0));
            document.querySelectorAll('.credit-input').forEach(input => totalCredit += parseFloat(input.value || 0));

            const fmt = (val) => val.toLocaleString('es-CO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            document.getElementById('total-debit').innerText = '$' + fmt(totalDebit);
            document.getElementById('total-credit').innerText = '$' + fmt(totalCredit);

            const badge = document.getElementById('balance-badge');
            const saveBtn = document.getElementById('save-btn');
            const diff = Math.abs(totalDebit - totalCredit);

            if(diff < 0.01 && totalDebit > 0) {
                badge.className = "flex items-center gap-4 px-8 py-4 rounded-2xl border bg-emerald-500/10 border-emerald-500/30 text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.1)]";
                badge.innerHTML = '<i class="fas fa-check-double text-sm"></i> <span class="text-[10px] font-black uppercase tracking-[0.2em]">Balance Cuadrado</span>';
                
                saveBtn.disabled = false;
                saveBtn.className = "px-12 py-5 bg-[#f3c444] text-black text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl shadow-[#f3c444]/20 cursor-pointer opacity-100";
            } else {
                badge.className = "flex items-center gap-4 px-8 py-4 rounded-2xl border bg-rose-500/10 border-rose-500/20 text-rose-500";
                badge.innerHTML = '<i class="fas fa-exclamation-triangle animate-pulse text-sm"></i> <span class="text-[10px] font-black uppercase tracking-[0.2em]">Asiento Desbalanceado</span>';
                
                saveBtn.disabled = true;
                saveBtn.className = "px-12 py-5 bg-zinc-800 text-zinc-600 text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl transition-all cursor-not-allowed opacity-50 shadow-none";
            }
        }

        // Evento Delegado para inputs numéricos
        document.getElementById('lines-body').addEventListener('input', e => {
            if(e.target.classList.contains('debit-input') || e.target.classList.contains('credit-input')) {
                // Si escriben en Débito, limpiamos Crédito de esa fila y viceversa (opcional según flujo)
                /*
                const row = e.target.closest('tr');
                if(e.target.classList.contains('debit-input') && parseFloat(e.target.value) > 0) 
                    row.querySelector('.credit-input').value = 0;
                */
                calculateTotals();
            }
        });

        // Inicializar con dos filas por defecto para agilizar
        document.addEventListener('DOMContentLoaded', () => {
            addRow();
            addRow();
        });
    </script>

    <style>
        .font-mono { font-family: 'JetBrains Mono', 'Fira Code', monospace; }
        input[type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none"]::-webkit-inner-spin-button, 
        input[type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none"]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        select { background-image: none !important; }
    </style>
</x-app-layout>