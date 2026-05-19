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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-yellow-500/20 blur-2xl rounded-full"></div>
                    <div class="relative bg-[#0a0a0c] p-4 rounded-2xl border border-yellow-500/40 shadow-2xl">
                        <i class="fas fa-file-invoice-dollar text-yellow-500 text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        RECIBO DE <span class="text-yellow-500">CAJA</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.4em] mt-2 font-black">
                        COMPROBANTE OFICIAL: <span class="text-white">#REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 print:hidden">
                <button onclick="window.print()" 
                        class="flex items-center gap-3 bg-yellow-500 hover:bg-white text-black px-6 py-4 rounded-xl font-black uppercase text-[10px] tracking-[0.2em] transition-all shadow-[0_10px_20px_-5px_rgba(234,179,8,0.4)] active:scale-95">
                    <i class="fas fa-print"></i> IMPRIMIR RECIBO
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-[#050507] min-h-screen relative overflow-hidden font-sans">
        {{-- Malla decorativa --}}
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-4xl mx-auto px-6 relative z-10">
            
            {{-- CUERPO DEL RECIBO --}}
            <div class="bg-[#0a0a0c] border border-white/5 rounded-[3rem] overflow-hidden shadow-2xl shadow-black/50">
                
                {{-- CABECERA INTERNA --}}
                <div class="p-10 border-b border-white/5 bg-white/[0.01]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.3em] mb-2">TITULAR DEL PAGO</p>
                            <h3 class="text-2xl font-black text-white uppercase  tracking-tighter">
                                {{ $payment->invoice->customer->name ?? 'CLIENTE GENERAL' }}
                            </h3>
                            <p class="text-xs text-zinc-500 font-bold mt-1">Identificación: {{ $payment->invoice->customer->nit ?? 'S.A.S' }}</p>
                        </div>
                        <div class="md:text-right">
                            <p class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.3em] mb-2">FECHA DE RECAUDO</p>
                            <h3 class="text-xl font-black text-zinc-300 font-mono">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d / m / Y') }}
                            </h3>
                            @php
                                $metodo = match(strtolower($payment->payment_method)) {
                                    'cash', 'efectivo' => 'EFECTIVO',
                                    'transfer', 'transferencia' => 'TRANSFERENCIA BANCARIA',
                                    'nequi' => 'NEQUI / DAVIPLATA',
                                    'card', 'tarjeta' => 'TARJETA DÉBITO/CRÉDITO',
                                    default => strtoupper($payment->payment_method)
                                };
                            @endphp
                            <div class="mt-4 inline-block bg-yellow-500 text-black px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                                {{ $metodo }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DETALLE DE FACTURAS --}}
                <div class="p-10">
                    <h4 class="text-[10px] font-black text-zinc-600 uppercase tracking-[0.4em] mb-6 flex items-center gap-3">
                        <span class="w-8 h-[1px] bg-zinc-800"></span>
                        FACTURAS AFECTADAS POR ESTE PAGO
                    </h4>

                    <div class="overflow-hidden rounded-2xl border border-white/5">
                        <table class="w-full">
                            <thead class="bg-white/[0.03]">
                                <tr class="text-left">
                                    <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-widest">Folio</th>
                                    <th class="px-6 py-4 text-right text-[9px] font-black text-zinc-500 uppercase tracking-widest">Valor Factura</th>
                                    <th class="px-6 py-4 text-right text-[9px] font-black text-zinc-500 uppercase tracking-widest text-yellow-500">Abono Aplicado</th>
                                    <th class="px-6 py-4 text-right text-[9px] font-black text-zinc-500 uppercase tracking-widest text-red-500">Saldo Nuevo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($payment->allocations as $allocation)
                                @php
                                    $invoice = $allocation->invoice;
                                    $paid = $invoice->payments->sum('amount');
                                    $balance = $invoice->total - $paid;
                                @endphp
                                <tr class="bg-zinc-950/50">
                                    <td class="px-6 py-5">
                                        <span class="text-sm font-black text-zinc-300 font-mono">#{{ $invoice->number }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-right font-mono text-xs text-zinc-400">
                                        ${{ number_format($invoice->total, 0) }}
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-sm font-black text-yellow-500 font-mono ">
                                            + ${{ number_format($allocation->amount, 0) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-sm font-black text-red-500 font-mono tracking-tighter">
                                            ${{ number_format($balance, 0) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- REFERENCIAS Y NOTAS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                        <div class="space-y-4">
                            @if($payment->reference)
                                <div>
                                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">REFERENCIA DE TRANSACCIÓN</p>
                                    <p class="text-sm font-bold text-zinc-300">{{ $payment->reference }}</p>
                                </div>
                            @endif
                            @if($payment->notes)
                                <div>
                                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">NOTAS ADICIONALES</p>
                                    <p class="text-xs text-zinc-500 leading-relaxed ">"{{ $payment->notes }}"</p>
                                </div>
                            @endif
                        </div>

                        {{-- GRAN TOTAL --}}
                        <div class="bg-white/[0.02] p-8 rounded-[2rem] border border-white/5 text-right relative overflow-hidden group">
                            <div class="relative z-10">
                                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em] mb-2">TOTAL RECAUDADO</p>
                                <h2 class="text-5xl font-black text-white font-mono tracking-tighter  group-hover:text-yellow-500 transition-colors">
                                    ${{ number_format($payment->amount, 0) }}
                                </h2>
                                <p class="text-[8px] font-black text-yellow-500/50 uppercase tracking-[0.2em] mt-2">PESOS COLOMBIANOS M/CTE</p>
                            </div>
                            <i class="fas fa-check-circle absolute -left-4 -bottom-4 text-7xl text-white/[0.02] group-hover:text-yellow-500/[0.05] transition-all"></i>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES DE PIE --}}
                <div class="p-10 bg-white/[0.02] border-t border-white/5 flex flex-col md:flex-row gap-4 justify-between items-center print:hidden">
                    <a href="{{ route('invoices.payments.index', $invoice) }}" 
                       class="text-[10px] font-black text-zinc-500 uppercase tracking-widest hover:text-white flex items-center gap-2 transition-all">
                        <i class="fas fa-arrow-left"></i> REGRESAR
                    </a>
                    <div class="flex gap-4">
                         <button class="px-6 py-3 bg-zinc-900 border border-white/5 text-zinc-400 rounded-xl text-[9px] font-black uppercase hover:text-white hover:border-white/20 transition-all">
                            DESCARGAR PDF
                         </button>
                    </div>
                </div>
            </div>
            
            <p class="text-center text-[9px] text-zinc-700 font-black uppercase tracking-[0.5em] mt-10">
                SISTEMA DE GESTIÓN AVÍCOLA 2026 - TODOS LOS DERECHOS RESERVADOS
            </p>
        </div>
    </div>

    <style>
        @media print {
            .print\:hidden { display: none !important; }
            body { background: white !important; }
            .bg-\[\#0a0a0c\] { background: transparent !important; color: black !important; border: 1px solid #eee !important; }
            .text-white, .text-zinc-300 { color: black !important; }
            .text-zinc-500, .text-zinc-600 { color: #666 !important; }
            .bg-white\/\[0\.01\], .bg-white\/\[0\.02\], .bg-white\/\[0\.03\] { background: #f9f9f9 !important; }
            .border-white\/5 { border-color: #eee !important; }
            .shadow-2xl { shadow: none !important; }
        }
    </style>
</x-app-layout>