{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('cartera.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Estado de <span class="text-yellow-500">Cuenta</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        {{ $customer->name }}
                    </p>
                </div>
            </div>
            {{-- Botón WhatsApp cobro manual --}}
            @if($customer->phone)
            <form method="POST" action="{{ route('cartera.whatsapp-reminder', $customer) }}">
                @csrf
                <button type="submit"
                    class="h-9 px-4 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 text-emerald-400 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fab fa-whatsapp text-sm"></i> Enviar Cobro
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0d121f] border border-rose-500/10 rounded-2xl p-5">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Saldo Pendiente Total</p>
                <p class="text-3xl font-black text-rose-500 tracking-tighter">${{ number_format($totalDebt, 0, ',', '.') }}</p>
                <div class="mt-3 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500 w-full"></div>
                </div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Facturas Activas</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-3xl font-black text-white tracking-tighter">{{ $customer->invoices->count() }}</p>
                    <span class="text-sm font-black text-yellow-500 uppercase">Docs</span>
                </div>
                <div class="mt-3 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500" style="width: 40%"></div>
                </div>
            </div>
            <div class="bg-[#0d121f] border border-emerald-500/10 rounded-2xl p-5">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Histórico Recaudado</p>
                <p class="text-3xl font-black text-emerald-500 tracking-tighter">${{ number_format($customer->invoices->sum('total') - $totalDebt, 0, ',', '.') }}</p>
                <div class="mt-3 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 w-full"></div>
                </div>
            </div>
        </div>

        {{-- Tabla de documentos --}}
        @if($customer->invoices->isNotEmpty())
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Referencia</th>
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Fechas</th>
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Bruto</th>
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Abonado</th>
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Neto</th>
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Estado</th>
                                <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-right">Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @foreach($customer->invoices as $invoice)
                                @php
                                    $status = match(true) {
                                        $invoice->balance <= 0                                     => 'paid',
                                        $invoice->due_date && $invoice->due_date < now()           => 'overdue',
                                        $invoice->payment_status === 'partial'                     => 'partial',
                                        default                                                    => 'pending',
                                    };
                                    $stLabel = match($status) {
                                        'paid'    => 'Completo',
                                        'partial' => 'Parcial',
                                        'overdue' => 'Vencido',
                                        default   => 'Pendiente',
                                    };
                                    $stClass = match($status) {
                                        'paid'    => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                        'partial' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                        'overdue' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                        default   => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20',
                                    };
                                @endphp
                                <tr class="hover:bg-white/[0.01] transition-colors group">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-lg bg-black/40 border border-white/5 flex items-center justify-center group-hover:border-yellow-500/30 transition-colors">
                                                <i class="fas fa-file-invoice text-[9px] text-gray-600 group-hover:text-yellow-500 transition-colors"></i>
                                            </div>
                                            <span class="text-sm font-black text-white">#{{ $invoice->id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="text-[9px] font-black text-gray-600 uppercase">E: {{ optional($invoice->created_at)->format('d/m/y') }}</p>
                                        <p class="text-[10px] font-black {{ $status === 'overdue' ? 'text-rose-500' : 'text-gray-400' }} uppercase">
                                            V: {{ optional($invoice->due_date)->format('d/m/y') }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-black text-white">${{ number_format($invoice->total, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-black text-emerald-500">${{ number_format($invoice->total - $invoice->balance, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-black {{ $invoice->balance > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                            ${{ number_format($invoice->balance, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $stClass }}">
                                            {{ $stLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                           class="h-8 w-8 inline-flex items-center justify-center bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-yellow-500 hover:border-yellow-500/30 transition-all">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Historial de abonos --}}
                                @if($invoice->payments->isNotEmpty())
                                    <tr class="bg-black/10">
                                        <td colspan="7" class="px-5 py-3 border-l-2 border-yellow-500/20">
                                            <div class="flex flex-wrap gap-2 items-center">
                                                <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest mr-1">Abonos:</span>
                                                @foreach($invoice->payments as $payment)
                                                    <div class="flex items-center gap-2 bg-white/[0.03] px-3 py-1.5 rounded-xl border border-white/5 hover:border-emerald-500/20 transition-colors">
                                                        <i class="fas fa-check text-emerald-500 text-[8px]"></i>
                                                        <span class="text-[9px] font-black text-white">${{ number_format($payment->amount, 0, ',', '.') }}</span>
                                                        <span class="text-[8px] text-gray-600 uppercase">{{ $payment->created_at->format('d M') }} · {{ strtoupper($payment->payment_method) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="py-16 text-center bg-[#0d121f] border border-white/5 rounded-2xl">
                <i class="fas fa-receipt text-4xl text-gray-800 block mb-3"></i>
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin movimientos registrados</p>
            </div>
        @endif

    </div>
</x-app-layout>
