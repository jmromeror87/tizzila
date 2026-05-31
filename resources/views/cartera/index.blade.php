{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Cartera <span class="text-yellow-500">&amp; Cobros</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Gestión de Cuentas por Cobrar
                </p>
            </div>
            <a href="{{ route('cartera.dashboard') }}"
               class="h-9 px-5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest w-fit">
                <i class="fas fa-chart-line text-yellow-500 text-xs"></i> Analytics
            </a>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- Filtros --}}
        <form method="GET">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-4">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Cliente</label>
                    <select name="customer_id"
                            class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer">
                        <option value="">Todos los clientes</option>
                        @foreach($customers as $id => $name)
                            <option value="{{ $id }}" @selected(request('customer_id') == $id)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Estado</label>
                    <select name="status"
                            class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer">
                        <option value="">Todos</option>
                        <option value="pending" @selected(request('status') == 'pending')>Pendiente</option>
                        <option value="partial" @selected(request('status') == 'partial')>Pago Parcial</option>
                        <option value="overdue" @selected(request('status') == 'overdue')>Vencido</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Rango de Fecha</label>
                    <div class="flex items-center bg-black/30 border border-white/10 rounded-xl px-3 gap-2">
                        <input type="date" name="from" value="{{ request('from') }}"
                               class="bg-transparent border-none text-white text-xs py-2.5 focus:ring-0 focus:outline-none flex-1">
                        <span class="text-gray-700 text-xs">/</span>
                        <input type="date" name="to" value="{{ request('to') }}"
                               class="bg-transparent border-none text-white text-xs py-2.5 focus:ring-0 focus:outline-none flex-1">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <button type="submit"
                            class="w-full h-9 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all active:scale-95">
                        Aplicar
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabla --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Cliente / Detalle</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Saldo Pendiente</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Vencimiento</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Estado</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-right">Gestión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($invoices as $invoice)
                            @php
                                $stLabel = match($invoice->payment_status ?? 'pending') {
                                    'paid'    => 'Pagada',
                                    'partial' => 'Pago Parcial',
                                    'pending' => 'Pendiente',
                                    default   => 'Vencido',
                                };
                                $stClass = match($invoice->payment_status ?? 'pending') {
                                    'paid'    => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'partial' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                    default   => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                };
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-black text-white uppercase">{{ $invoice->customer->name }}</p>
                                    <p class="text-[9px] text-gray-600 tracking-widest">#{{ $invoice->id }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-black text-rose-500">${{ number_format($invoice->balance, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-[10px] font-black text-gray-400">{{ $invoice->due_date->translatedFormat('d M, Y') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $stClass }}">
                                        {{ $stLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('cartera.customer', $invoice->customer_id) }}"
                                           class="h-8 px-3 bg-blue-500/10 border border-blue-500/20 rounded-lg text-blue-400 text-[9px] font-black uppercase hover:bg-blue-500 hover:text-white transition-all flex items-center gap-1.5">
                                            <i class="fas fa-user-tag text-[9px]"></i> Cliente
                                        </a>
                                        @if($invoice->balance > 0)
                                            <button onclick="openPaymentModal({{ $invoice->id }}, {{ $invoice->balance }})"
                                                    class="h-8 px-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-500 text-[9px] font-black uppercase hover:bg-emerald-500 hover:text-white transition-all flex items-center gap-1.5">
                                                <i class="fas fa-cash-register text-[9px]"></i> Cobrar
                                            </button>
                                        @endif
                                        @if($invoice->payment_status === 'overdue' && $invoice->customer?->phone)
                                        <form method="POST" action="{{ route('cartera.whatsapp-reminder', $invoice->customer_id) }}">
                                            @csrf
                                            <button type="submit" title="Enviar recordatorio WhatsApp"
                                                class="h-8 w-8 bg-emerald-600/10 border border-emerald-600/20 rounded-lg text-emerald-500 hover:bg-emerald-600/20 flex items-center justify-center transition-all">
                                                <i class="fab fa-whatsapp text-sm"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <i class="fas fa-check-circle text-emerald-500 text-3xl block mb-3 opacity-20"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin cuentas pendientes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal de pago --}}
    <div id="paymentModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-[#0d121f] rounded-2xl w-full max-w-md border border-white/10 shadow-2xl">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="h-9 w-9 bg-yellow-500/10 rounded-xl flex items-center justify-center text-yellow-500 border border-yellow-500/20">
                        <i class="fas fa-money-bill-wave text-sm"></i>
                    </div>
                    <h2 class="text-base font-black text-white uppercase">Registrar <span class="text-yellow-500">Abono</span></h2>
                </div>

                <form id="paymentForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="amount" id="real_amount">
                    <div>
                        <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Monto a abonar (COP)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-black text-sm pointer-events-none">$</span>
                            <input type="text" id="display_amount"
                                   class="w-full bg-black/30 border border-white/10 rounded-xl text-white text-lg font-black pl-8 pr-4 py-3 focus:outline-none focus:border-yellow-500/50 transition-colors"
                                   placeholder="0" autocomplete="off">
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Método de Pago</label>
                        <select name="payment_method"
                                class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl text-white text-xs font-black px-4 py-3 focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer uppercase tracking-widest">
                            <option value="transfer">Transferencia</option>
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closePaymentModal()"
                                class="h-9 px-5 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase rounded-xl hover:bg-white/10 transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="h-9 px-6 bg-emerald-500 hover:bg-emerald-400 text-black text-[10px] font-black uppercase rounded-xl transition-all active:scale-95">
                            Confirmar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>

    <script>
        const displayAmount = document.getElementById('display_amount');
        const realAmount    = document.getElementById('real_amount');

        function formatCOP(v) {
            if (!v) return '';
            return new Intl.NumberFormat('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(v);
        }

        displayAmount.addEventListener('input', e => {
            const v = e.target.value.replace(/\D/g, '');
            realAmount.value  = v;
            e.target.value    = formatCOP(v);
        });

        function openPaymentModal(invoiceId, balance) {
            document.getElementById('paymentForm').action = `/cartera/pagar/${invoiceId}`;
            realAmount.value    = balance;
            displayAmount.value = formatCOP(balance);
            const m = document.getElementById('paymentModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closePaymentModal() {
            const m = document.getElementById('paymentModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
    </script>
</x-app-layout>
