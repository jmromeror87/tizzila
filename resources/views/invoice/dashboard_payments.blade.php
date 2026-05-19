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
<div class="py-10 bg-[#050507] min-h-screen relative overflow-hidden font-sans">

    <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
        style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 30px 30px;">
    </div>

    <div class="max-w-[1400px] mx-auto px-6 relative z-10 space-y-8">

        {{-- MENSAJES --}}
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl text-xs font-bold flex items-center gap-3">
                <i class="fas fa-circle-check text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 px-6 py-4 rounded-2xl text-xs font-bold flex items-center gap-3">
                <i class="fas fa-triangle-exclamation text-rose-500"></i> {{ session('error') }}
            </div>
        @endif

        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="relative bg-[#0a0a0c] p-4 rounded-2xl border border-yellow-500/50 shadow-[0_0_20px_rgba(234,179,8,0.1)]">
                    <i class="fas fa-receipt text-yellow-500 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        AUDITORÍA DE <span class="text-yellow-500">RECAUDOS</span>
                    </h2>
                    <p class="text-[8px] text-zinc-500 uppercase tracking-[0.4em] mt-2 font-black">
                        Documento Relacionado: <span class="text-zinc-300">#{{ $invoice->number }}</span>
                        — Cliente: <span class="text-zinc-300">{{ $invoice->customer->name ?? '—' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex gap-4 items-center">
                <a href="{{ route('invoices.index') }}"
                    class="px-6 py-3 bg-white/5 border border-white/10 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:bg-white/10 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>

                {{-- ✅ BUG 2: ocultar botón si ya está pagada --}}
                @if($invoice->payment_status !== 'paid')
                    <a href="{{ route('invoices.payments.create', $invoice->id) }}"
                        class="px-8 py-3 bg-yellow-500 hover:bg-white text-black rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-[0_10px_20px_-5px_rgba(234,179,8,0.4)] flex items-center gap-2">
                        <i class="fas fa-plus"></i> Registrar Abono
                    </a>
                @else
                    <span class="px-8 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-check-double"></i> Factura Pagada
                    </span>
                @endif
            </div>
        </div>

        @php
            // ✅ BUG 1: usar balance directamente del modelo — ya lo calcula el service
            $total   = (float) $invoice->total;
            $paid    = (float) ($total - $invoice->balance);  // lo pagado = total - saldo
            $balance = (float) $invoice->balance;
            $percent = $total > 0 ? min(100, ($paid / $total) * 100) : 0;
        @endphp

        {{-- RESUMEN FINANCIERO --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-[#0a0a0c] border border-white/5 p-6 rounded-[2rem]">
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Cliente</p>
                <p class="text-lg font-black text-white truncate uppercase">{{ $invoice->customer->name ?? '—' }}</p>
                <p class="text-[9px] text-zinc-600 mt-1">NIT: {{ $invoice->customer->nit ?? 'N/A' }}</p>
            </div>

            <div class="bg-[#0a0a0c] border border-white/5 p-6 rounded-[2rem]">
                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Total Documento</p>
                <p class="text-2xl font-black text-white font-mono tracking-tighter">${{ number_format($total, 0, ',', '.') }}</p>
            </div>

            <div class="bg-[#0a0a0c] border border-white/5 p-6 rounded-[2rem] relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[9px] font-black text-emerald-500/70 uppercase tracking-widest mb-1">Total Recaudado</p>
                    <p class="text-2xl font-black text-emerald-500 font-mono tracking-tighter">${{ number_format($paid, 0, ',', '.') }}</p>
                </div>
                <div class="absolute right-[-10%] bottom-[-20%] opacity-5 text-emerald-500">
                    <i class="fas fa-check-double text-6xl"></i>
                </div>
            </div>

            <div class="bg-[#0a0a0c] border {{ $balance <= 0 ? 'border-emerald-500/20' : 'border-red-500/20' }} p-6 rounded-[2rem] relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[9px] font-black {{ $balance <= 0 ? 'text-emerald-400' : 'text-red-400' }} uppercase tracking-widest mb-1">Saldo Remanente</p>
                    <p class="text-2xl font-black {{ $balance <= 0 ? 'text-emerald-500' : 'text-red-500' }} font-mono tracking-tighter">${{ number_format($balance, 0, ',', '.') }}</p>
                </div>
                <div class="absolute right-4 top-6">
                    @if($balance <= 0)
                        <span class="flex h-3 w-3 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                    @elseif($paid > 0)
                        <span class="flex h-3 w-3 rounded-full bg-yellow-500 animate-pulse"></span>
                    @else
                        <span class="flex h-3 w-3 rounded-full bg-red-600"></span>
                    @endif
                </div>
            </div>
        </div>

        {{-- BARRA DE PROGRESO --}}
        <div class="space-y-1">
            <div class="flex justify-between text-[9px] font-black text-zinc-600 uppercase tracking-widest">
                <span>Progreso de Pago</span>
                <span class="{{ $percent >= 100 ? 'text-emerald-500' : 'text-yellow-500' }}">{{ number_format($percent, 1) }}%</span>
            </div>
            <div class="bg-zinc-900/30 rounded-full h-2 w-full overflow-hidden border border-white/5">
                <div class="h-full {{ $percent >= 100 ? 'bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)]' : 'bg-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.3)]' }} transition-all duration-1000"
                    style="width: {{ $percent }}%">
                </div>
            </div>
        </div>

        {{-- TABLA DE HISTORIAL --}}
        <div class="bg-[#0a0a0c] border border-white/5 rounded-[2.5rem] overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.3em]">Registro Cronológico de Movimientos</h3>
                <span class="text-[9px] font-bold text-zinc-600 font-mono">
                    Registros totales: {{ $payments->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-white/5">
                            <th class="px-8 py-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest">Fecha</th>
                            <th class="px-8 py-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest">Método</th>
                            <th class="px-8 py-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest">Referencia</th>
                            <th class="px-8 py-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest text-right">Monto</th>
                            <th class="px-8 py-4 text-[9px] font-black text-zinc-600 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($payments as $allocation)
                        <tr class="hover:bg-white/[0.01] transition-colors group">
                            <td class="px-8 py-5">
                                {{-- ✅ BUG 3: payment_date formateado --}}
                                <span class="text-xs font-black text-zinc-300 font-mono">
                                    {{ \Carbon\Carbon::parse($allocation->payment->payment_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $metodos = [
                                        'cash'      => 'Efectivo',
                                        'transfer'  => 'Transferencia',
                                        'card'      => 'Tarjeta',
                                        'other'     => 'Otro',
                                    ];
                                    $method = $allocation->payment->payment_method ?? 'other';
                                    $displayMethod = $metodos[$method] ?? str_replace('_', ' ', $method);
                                @endphp
                                <span class="px-3 py-1 rounded-md bg-zinc-900 border border-white/5 text-[9px] font-black text-zinc-400 uppercase tracking-widest">
                                    {{ $displayMethod }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-zinc-500 font-bold text-[10px] uppercase">
                                {{ $allocation->payment->reference ?? '---' }}
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-sm font-black text-white font-mono">
                                    ${{ number_format($allocation->amount_applied, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('payments.show', $allocation->payment) }}"
                                    class="text-[9px] font-black text-zinc-500 hover:text-yellow-500 uppercase tracking-widest transition-colors">
                                    Ver recibo
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- ✅ BUG 4: colspan correcto = 5 --}}
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <i class="fas fa-folder-open text-5xl text-zinc-500"></i>
                                    <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.4em]">Sin movimientos financieros detectados</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: #1a1a1c; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #eab308; }
</style>
</x-app-layout>