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
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('customers.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        {{ $customer->name }}
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1 flex items-center gap-2">
                        <i class="fas fa-id-card text-yellow-500 text-[8px]"></i>
                        {{ $customer->identification_number }}@if($customer->dv)-{{ $customer->dv }}@endif
                        &nbsp;·&nbsp; Expediente del Cliente
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customers.edit', $customer) }}"
                   class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-pen"></i> Editar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- ══════════════════════════════════════════════ --}}
        {{-- ROW 1: SCORE + KPIs FINANCIEROS --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

            {{-- Score Card --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center text-center col-span-1">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-3">Score de Pago</p>
                <div class="relative h-28 w-28 mb-3">
                    <canvas id="scoreChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-2xl font-black {{ $scoreLabel[1] }} leading-none">{{ $scorePct }}%</p>
                        <p class="text-[8px] text-zinc-600 font-black uppercase mt-0.5">Score</p>
                    </div>
                </div>
                <span class="text-[9px] font-black px-3 py-1 rounded-lg {{ $scoreLabel[2] }} {{ $scoreLabel[3] }} border {{ $scoreLabel[1] }}">
                    {{ $scoreLabel[0] }}
                </span>
                <p class="text-[9px] text-zinc-600 mt-2">{{ $paidCount }}/{{ $totalInvoices }} facturas pagadas</p>
            </div>

            {{-- KPIs --}}
            <div class="col-span-1 lg:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 hover:border-yellow-500/20 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center mb-3">
                        <i class="fas fa-file-invoice-dollar text-yellow-500 text-xs"></i>
                    </div>
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Total Facturado</p>
                    <p class="text-xl font-black text-white tracking-tighter">${{ number_format($totalInvoiced, 0) }}</p>
                    <p class="text-[9px] text-zinc-700 mt-1">{{ $totalInvoices }} facturas</p>
                </div>

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 hover:border-emerald-500/20 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-3">
                        <i class="fas fa-circle-check text-emerald-400 text-xs"></i>
                    </div>
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Total Pagado</p>
                    <p class="text-xl font-black text-emerald-400 tracking-tighter">${{ number_format($totalPaid, 0) }}</p>
                    <p class="text-[9px] text-zinc-700 mt-1">{{ $paidCount }} al día</p>
                </div>

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 hover:border-amber-500/20 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-3">
                        <i class="fas fa-clock-rotate-left text-amber-400 text-xs"></i>
                    </div>
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Saldo Pendiente</p>
                    <p class="text-xl font-black text-amber-400 tracking-tighter">${{ number_format($totalBalance, 0) }}</p>
                    <p class="text-[9px] text-zinc-700 mt-1">{{ $pendingCount }} pendiente{{ $pendingCount !== 1 ? 's' : '' }}</p>
                </div>

                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 hover:border-red-500/20 transition-all">
                    <div class="h-8 w-8 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mb-3">
                        <i class="fas fa-triangle-exclamation text-red-400 text-xs"></i>
                    </div>
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Vencidas</p>
                    <p class="text-xl font-black {{ $overdueCount > 0 ? 'text-red-400' : 'text-zinc-600' }} tracking-tighter">{{ $overdueCount }}</p>
                    <p class="text-[9px] text-zinc-700 mt-1">facturas vencidas</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- ROW 2: DATOS DEL CLIENTE + CONDICIONES --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Datos tributarios --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden col-span-2">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                    <i class="fas fa-id-card text-yellow-500 text-xs"></i>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Datos del Cliente</h3>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4">
                    @php
                        $fields = [
                            ['Razón Social',        $customer->name],
                            ['NIT / Documento',     $customer->identification_number . ($customer->dv ? '-'.$customer->dv : '')],
                            ['Tipo Documento',      $customer->type_document_id],
                            ['Organización',        $customer->type_organization_id == 1 ? 'Persona Jurídica' : 'Persona Natural'],
                            ['Régimen',             $customer->type_regime_id],
                            ['Responsabilidad',     $customer->type_liability_id],
                            ['Municipio',           $customer->municipality_id],
                            ['Dirección',           $customer->address],
                            ['Código Postal',       $customer->postal_code ?? '—'],
                            ['Correo',              $customer->email],
                            ['Teléfono',            $customer->phone ?? '—'],
                            ['Plazo de Pago',       $customer->paymentTerm?->name ?? '—'],
                        ];
                    @endphp
                    @foreach($fields as [$label, $value])
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-0.5">{{ $label }}</p>
                            <p class="text-xs font-bold text-zinc-300 break-words">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Condiciones comerciales + barra de saldo --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                    <i class="fas fa-chart-pie text-yellow-500 text-xs"></i>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Estado Financiero</h3>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    {{-- Barra pagado vs pendiente --}}
                    <div class="mb-6">
                        <div class="flex justify-between text-[9px] font-black uppercase tracking-widest mb-2">
                            <span class="text-emerald-400">Pagado</span>
                            <span class="text-amber-400">Pendiente</span>
                        </div>
                        @php $paidPct = $totalInvoiced > 0 ? round(($totalPaid/$totalInvoiced)*100) : 0; @endphp
                        <div class="h-3 bg-black/50 rounded-full overflow-hidden flex">
                            <div class="h-full bg-emerald-500 rounded-l-full transition-all" style="width: {{ $paidPct }}%"></div>
                            <div class="h-full bg-amber-500/40 flex-1 rounded-r-full"></div>
                        </div>
                        <div class="flex justify-between mt-1.5 text-[9px] font-black">
                            <span class="text-emerald-400">${{ number_format($totalPaid, 0) }}</span>
                            <span class="text-amber-400">${{ number_format($totalBalance, 0) }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Plazo acordado</p>
                            <p class="text-xs font-black text-white">{{ $customer->paymentTerm?->name ?? '—' }}</p>
                        </div>
                        @if($customer->credit_limit > 0)
                        @php
                            $cupo = $customer->credit_limit;
                            $usado = $totalBalance; // saldo pendiente
                            $pct = $cupo > 0 ? min(100, round(($usado / $cupo) * 100)) : 0;
                            $color = $pct >= 90 ? 'bg-red-500' : ($pct >= 60 ? 'bg-yellow-500' : 'bg-emerald-500');
                            $textColor = $pct >= 90 ? 'text-red-400' : ($pct >= 60 ? 'text-yellow-400' : 'text-emerald-400');
                        @endphp
                        <div class="py-2 border-b border-white/5 space-y-1.5">
                            <div class="flex justify-between items-center">
                                <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Cupo de crédito</p>
                                <p class="text-xs font-black text-yellow-400">${{ number_format($cupo, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between items-center text-[9px] font-black">
                                <span class="text-zinc-500">Usado: <span class="{{ $textColor }}">${{ number_format($usado, 0, ',', '.') }}</span></span>
                                <span class="{{ $textColor }}">{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $color }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex justify-between text-[8px] text-zinc-600 font-black">
                                <span>Disponible: ${{ number_format(max(0, $cupo - $usado), 0, ',', '.') }}</span>
                                <span class="{{ $pct >= 90 ? 'text-red-400' : '' }}">{{ $pct >= 90 ? '⚠ CUPO AGOTADO' : ($pct >= 60 ? 'CUPO ALTO' : 'CUPO OK') }}</span>
                            </div>
                        </div>
                        @else
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Cupo de crédito</p>
                            <p class="text-[9px] font-black text-zinc-500">Sin cupo asignado</p>
                        </div>
                        @endif
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Coordenadas</p>
                            <p class="text-[9px] font-mono text-zinc-500">{{ $customer->latitude }}, {{ $customer->longitude }}</p>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Tipo de cliente</p>
                            @if($customer->is_fixed_client)
                                <span class="text-[9px] font-black px-2 py-0.5 rounded-lg text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 flex items-center gap-1">
                                    <i class="fas fa-star text-[8px]"></i> FIJO · {{ number_format($customer->fixed_weekly_quantity, 0) }} aves/sem
                                </span>
                            @else
                                <span class="text-[9px] font-black text-zinc-500">Variable</span>
                            @endif
                        </div>
                        @php
                            $hasOverdue = $overdueBalance > 0;
                            $overLimit  = $customer->credit_limit > 0 && $totalBalance > $customer->credit_limit;
                            $creditStatus = $hasOverdue ? 'blocked_overdue' : ($overLimit ? 'blocked_limit' : 'ok');
                        @endphp
                        @if($creditStatus !== 'ok')
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Alerta crédito</p>
                            <span class="text-[9px] font-black px-2 py-0.5 rounded-lg text-red-400 bg-red-500/10 border border-red-500/20 flex items-center gap-1">
                                <i class="fas fa-ban text-[8px]"></i>
                                {{ $creditStatus === 'blocked_overdue' ? 'DEUDA VENCIDA' : 'CUPO EXCEDIDO' }}
                            </span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center py-2">
                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">Estado</p>
                            <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $customer->is_active ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20' : 'text-red-400 bg-red-500/10 border border-red-500/20' }}">
                                {{ $customer->is_active ? 'ACTIVO' : 'INACTIVO' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- ROW 3: HISTORIAL DE FACTURAS --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-invoice text-yellow-500 text-xs"></i>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Historial de Compras</h3>
                </div>
                <span class="text-[9px] font-black text-zinc-500">{{ $totalInvoices }} facturas</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-6 py-3 text-left">Factura</th>
                            <th class="px-4 py-3 text-center">Fecha</th>
                            <th class="px-4 py-3 text-center">Vence</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-right">Pagado</th>
                            <th class="px-4 py-3 text-right">Saldo</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($customer->invoices->sortByDesc('issue_datetime') as $invoice)
                            @php
                                $paid = $invoice->total - $invoice->balance;
                                $statusMap = [
                                    'paid'    => ['PAGADA',    'text-emerald-400', 'bg-emerald-500/10', 'border-emerald-500/20'],
                                    'partial' => ['PARCIAL',   'text-blue-400',    'bg-blue-500/10',    'border-blue-500/20'],
                                    'pending' => ['PENDIENTE', 'text-amber-400',   'bg-amber-500/10',   'border-amber-500/20'],
                                    'overdue' => ['VENCIDA',   'text-red-400',     'bg-red-500/10',     'border-red-500/20'],
                                ];
                                [$stLabel, $stText, $stBg, $stBorder] = $statusMap[$invoice->payment_status] ?? ['—','text-zinc-500','bg-zinc-500/10','border-zinc-500/20'];
                                $isOverdue = $invoice->payment_status === 'overdue';
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors {{ $isOverdue ? 'border-l-2 border-red-500/40' : '' }} group">
                                <td class="px-6 py-3">
                                    <p class="text-xs font-black text-white font-mono">{{ $invoice->number }}</p>
                                    <p class="text-[9px] text-zinc-600">{{ $invoice->document_type }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <p class="text-xs text-zinc-300">{{ \Carbon\Carbon::parse($invoice->issue_datetime)->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <p class="text-xs {{ $isOverdue ? 'text-red-400 font-black' : 'text-zinc-500' }}">
                                        {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') : '—' }}
                                        @if($isOverdue)
                                            <span class="block text-[8px]">{{ \Carbon\Carbon::parse($invoice->due_date)->diffForHumans() }}</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <p class="text-xs font-black text-white">${{ number_format($invoice->total, 0) }}</p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <p class="text-xs font-black text-emerald-400">${{ number_format($paid, 0) }}</p>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <p class="text-xs font-black {{ $invoice->balance > 0 ? 'text-amber-400' : 'text-zinc-600' }}">
                                        {{ $invoice->balance > 0 ? '$'.number_format($invoice->balance, 0) : '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $stBg }} {{ $stBorder }} border {{ $stText }}">
                                        {{ $stLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if(Route::has('invoices.show'))
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="h-7 w-7 rounded-lg bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-600 flex items-center justify-center transition-all mx-auto opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-arrow-right text-[8px]"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>

                            {{-- Pagos de la factura --}}
                            @foreach($invoice->payments as $payment)
                                <tr class="bg-emerald-500/[0.02]">
                                    <td class="pl-12 pr-4 py-1.5" colspan="2">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-check-circle text-emerald-500/50 text-[9px]"></i>
                                            <span class="text-[9px] text-emerald-400/70 font-bold">Pago registrado</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-1.5 text-center">
                                        <span class="text-[9px] text-zinc-600">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</span>
                                    </td>
                                    <td colspan="2" class="px-4 py-1.5 text-right">
                                        <span class="text-[9px] font-black text-emerald-400">${{ number_format($payment->amount, 0) }}</span>
                                    </td>
                                    <td class="px-4 py-1.5 text-center" colspan="3">
                                        <span class="text-[9px] text-zinc-600 uppercase">{{ $payment->payment_method }}</span>
                                        @if($payment->reference)
                                            <span class="text-[9px] text-zinc-700"> · {{ $payment->reference }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-3 opacity-20">
                                        <i class="fas fa-file-invoice text-3xl"></i>
                                        <p class="text-[9px] font-black uppercase tracking-[0.3em]">Sin facturas registradas</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Totales del pie de tabla --}}
            @if($totalInvoices > 0)
            <div class="px-6 py-4 border-t border-white/5 bg-white/[0.01] grid grid-cols-3 gap-4">
                <div class="text-right col-start-2">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-0.5">Total Facturado</p>
                    <p class="text-sm font-black text-white">${{ number_format($totalInvoiced, 0) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-0.5">Saldo Total</p>
                    <p class="text-sm font-black {{ $totalBalance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">${{ number_format($totalBalance, 0) }}</p>
                </div>
            </div>
            @endif
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('scoreChart');
        if (!ctx) return;
        const pct = {{ $scorePct }};
        const colors = {
            'EXCELENTE': '#10b981',
            'BUENO':     '#3b82f6',
            'REGULAR':   '#eab308',
            'CRÍTICO':   '#ef4444',
        };
        const color = colors['{{ $scoreLabel[0] }}'] ?? '#eab308';
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [pct, 100 - pct],
                    backgroundColor: [color, 'rgba(255,255,255,0.04)'],
                    borderWidth: 0, borderRadius: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    });
    </script>
</x-app-layout>
