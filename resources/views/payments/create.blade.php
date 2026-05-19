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
            <div class="flex items-center gap-6">
                <div
                    class="relative bg-[#0a0a0c] p-3 rounded-xl border border-yellow-500/50 shadow-[0_0_15px_rgba(234,179,8,0.1)]">
                    <i class="fas fa-money-bill-transfer text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        GESTIÓN <span class="text-yellow-500">DE RECAUDOS</span>
                    </h2>
                    <p
                        class="text-[8px] text-zinc-500 uppercase tracking-[0.4em] mt-1 font-black flex items-center gap-2">
                        Tesorería <i class="fas fa-chevron-right text-[6px]"></i> Factura #{{ $invoice->number }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col items-end border-r-2 border-white/10 pr-6">
                    <span class="text-[10px] font-black text-zinc-600 uppercase tracking-widest">Saldo Actual</span>
                    <span class="text-xl font-black text-red-500 font-mono tracking-tighter">
                        ${{ number_format($invoice->total - $invoice->payments->sum('amount'), 2) }}
                    </span>
                </div>
                <a href="{{ route('invoices.show', $invoice->id) }}"
                    class="text-[10px] font-black text-zinc-400 uppercase tracking-widest hover:text-white transition-colors">
                    [ ESC ] Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#050507] min-h-screen relative overflow-hidden font-sans">
        {{-- Fondo con patrón de rejilla --}}
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
            style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 30px 30px;">
        </div>

        <div class="max-w-5xl mx-auto px-6 relative z-10 space-y-6">

            {{-- PANEL DE ESTADO --}}
            <div class="bg-[#0a0a0c] rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Titular de Cuenta</p>
                        <p class="text-xl font-black text-white  uppercase tracking-tight">{{ $invoice->customer->name
                            }}</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Valor Original</p>
                        <p class="text-xl font-black text-zinc-400 font-mono tracking-tight">${{
                            number_format($invoice->total, 2) }}</p>
                    </div>

                    <div class="bg-red-500/5 border border-red-500/10 p-4 rounded-2xl relative overflow-hidden">
                        <p class="text-[9px] font-black text-red-500/70 uppercase tracking-widest relative z-10">Saldo
                            Neto Pendiente</p>
                        <p class="text-3xl font-black text-red-500 tracking-tighter font-mono relative z-10">
                            ${{ number_format($invoice->total - $invoice->payments->sum('amount'), 2) }}
                        </p>
                        <i class="fas fa-exclamation-triangle absolute -right-2 -bottom-2 text-red-500/10 text-5xl"></i>
                    </div>
                </div>

                {{-- Barra de progreso visual --}}
                @php $percent = ($invoice->payments->sum('amount') / $invoice->total) * 100; @endphp
                <div class="h-1.5 w-full bg-zinc-900 flex">
                    <div class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] transition-all duration-1000"
                        style="width: {{ $percent }}%"></div>
                </div>
            </div>

            {{-- FORMULARIO DE RECAUDO --}}
            <div
                class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 p-8 md:p-12 shadow-2xl relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 p-10 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity pointer-events-none">
                    <i class="fas fa-cash-register text-[15rem] -rotate-12"></i>
                </div>

                <form method="POST" action="{{ route('payments.store', $invoice->id) }}" id="payment_form"
                    class="relative z-10">
                    @csrf

                    @php $balance = $invoice->total - $invoice->payments->sum('amount'); @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-10">

                        {{-- FECHA --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">Fecha de
                                Ingreso</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-calendar-day text-yellow-500/50 group-focus-within:text-yellow-500 transition-colors"></i>
                                </div>
                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full pl-12 pr-4 py-4 bg-zinc-900/50 border border-white/5 rounded-2xl focus:border-yellow-500 focus:ring-0 font-bold text-white transition-all">
                            </div>
                        </div>

                        {{-- MONTO --}}
                        <div class="space-y-3">
                            <div class="flex justify-between items-end px-1">
                                <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em]">Monto a
                                    Recaudar</label>
                                <button type="button" onclick="setFullPayment({{ $balance }})"
                                    class="text-[8px] font-black text-yellow-500 uppercase hover:underline">Abonar
                                    Total</button>
                            </div>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-dollar-sign text-yellow-500/50 group-focus-within:text-yellow-500 transition-colors"></i>
                                </div>
                                <input type="number" step="0.01" name="amount" id="amount_input" max="{{ $balance }}"
                                    placeholder="0.00" required
                                    oninput="updateProjectedBalance({{ $balance }}, this.value)"
                                    class="w-full pl-12 pr-4 py-4 bg-zinc-900/50 border border-white/5 rounded-2xl focus:border-yellow-500 focus:ring-0 font-black text-2xl text-white font-mono placeholder:text-zinc-800 transition-all">
                            </div>
                            <p class="text-[9px] text-zinc-600 font-bold " id="projected_text">Saldo proyectado tras
                                pago: <span class="text-zinc-400 font-mono">${{ number_format($balance, 2) }}</span></p>
                        </div>

                        {{-- MÉTODO --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">Canal de
                                Recepción</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-vault text-yellow-500/50 group-focus-within:text-yellow-500 transition-colors"></i>
                                </div>
                                <select name="payment_method" required
                                    class="w-full pl-12 pr-4 py-4 bg-zinc-900/50 border border-white/5 rounded-2xl focus:border-yellow-500 focus:ring-0 font-bold text-white appearance-none transition-all cursor-pointer">


                                    <option value="">Seleccione</option>

                                    <option value="cash">Efectivo</option>

                                    <option value="transfer">Transferencia bancaria</option>

                                    <option value="bank_deposit">Depósito bancario</option>

                                    <option value="card">Tarjeta</option>

                                    <option value="check">Cheque</option>

                                    <option value="other">Otro</option>


                                </select>

                            </div>
                        </div>

                        {{-- REFERENCIA --}}
                        <div class="space-y-3">
                            <label
                                class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">Referencia
                                Operativa</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-fingerprint text-yellow-500/50 group-focus-within:text-yellow-500 transition-colors"></i>
                                </div>
                                <input type="text" name="reference" placeholder="TRX-XXXXXX"
                                    class="w-full pl-12 pr-4 py-4 bg-zinc-900/50 border border-white/5 rounded-2xl focus:border-yellow-500 focus:ring-0 font-bold text-white uppercase placeholder:text-zinc-800 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-12">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] ml-1">Notas de
                            Conciliación</label>
                        <textarea name="notes" rows="3"
                            placeholder="Anotaciones internas del departamento de tesorería..."
                            class="w-full p-6 bg-zinc-900/50 border border-white/5 rounded-[2rem] focus:border-yellow-500 focus:ring-0 font-medium text-white placeholder:text-zinc-800 transition-all"></textarea>
                    </div>

                    <div
                        class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-white/5">
                        <div class="flex items-center gap-3 text-emerald-500/50 ">
                            <i class="fas fa-shield-halved text-sm"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">Transacción Cifrada &
                                Auditada</span>
                        </div>

                        <button type="submit"
                            class="w-full md:w-auto px-12 py-5 bg-yellow-500 hover:bg-white text-black rounded-2xl font-black uppercase tracking-[0.2em] text-[11px] transition-all shadow-[0_15px_30px_-10px_rgba(234,179,8,0.4)] flex items-center justify-center gap-4 group">
                            <span>PROCESAR RECAUDO</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateProjectedBalance(currentBalance, inputValue) {
            const amount = parseFloat(inputValue) || 0;
            const projected = currentBalance - amount;
            const display = document.getElementById('projected_text');
            
            if (projected < 0) {
                display.innerHTML = `<span class="text-red-500 font-black">ADVERTENCIA: El abono supera el saldo pendiente.</span>`;
            } else {
                display.innerHTML = `Saldo proyectado tras pago: <span class="text-emerald-500 font-mono">$${projected.toLocaleString('en-US', {minimumFractionDigits: 2})}</span>`;
            }
        }

        function setFullPayment(balance) {
            const input = document.getElementById('amount_input');
            input.value = balance.toFixed(2);
            updateProjectedBalance(balance, balance);
        }
    </script>

    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</x-app-layout>