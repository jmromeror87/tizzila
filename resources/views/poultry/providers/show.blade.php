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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.providers.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        {{ $provider->business_name }}
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                        <span class="text-yellow-500">{{ $provider->tax_id_type }}</span> {{ $provider->tax_id }}
                        · REF #{{ str_pad($provider->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('poultry.providers.edit', $provider) }}"
                   class="h-9 px-4 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-pen text-xs"></i> Editar
                </a>
                <a href="{{ route('poultry.orders.index') }}"
                   class="h-9 px-4 bg-yellow-500 hover:bg-yellow-400 text-black font-black rounded-xl transition-all text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-plus text-xs"></i> Nuevo Pedido
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @php
            $scoreColor = match(true) {
                $percentage >= 90 => ['text-emerald-400', 'bg-emerald-500', 'bg-emerald-500/10', 'border-emerald-500/20', 'EXCELENTE'],
                $percentage >= 70 => ['text-blue-400',    'bg-blue-500',    'bg-blue-500/10',    'border-blue-500/20',    'BUENO'],
                $percentage >= 40 => ['text-yellow-400',  'bg-yellow-500',  'bg-yellow-500/10',  'border-yellow-500/20',  'REGULAR'],
                default           => ['text-red-400',     'bg-red-500',     'bg-red-500/10',     'border-red-500/20',     'CRÍTICO'],
            };
        @endphp

        {{-- ALERTA rendimiento bajo --}}
        @if($percentage < 80 && $stats->total_orders > 0)
            <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl px-5 py-3 flex items-center gap-3">
                <i class="fas fa-triangle-exclamation text-amber-400"></i>
                <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest">
                    Índice de cumplimiento bajo: {{ $percentage }}% — Se recomienda revisión de pedidos pendientes.
                </p>
            </div>
        @endif

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Score Eficiencia</p>
                <div class="flex items-end gap-2">
                    <p class="text-3xl font-black {{ $scoreColor[0] }} leading-none">{{ $percentage }}<span class="text-lg">%</span></p>
                </div>
                <span class="inline-block mt-1 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded border {{ $scoreColor[2] }} {{ $scoreColor[3] }} {{ $scoreColor[0] }}">{{ $scoreColor[4] }}</span>
                <div class="mt-2 h-1.5 bg-black/40 rounded-full overflow-hidden">
                    <div class="h-full {{ $scoreColor[1] }} rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Total Pedidos</p>
                <p class="text-3xl font-black text-white leading-none">{{ number_format($stats->total_orders) }}</p>
                <p class="text-[9px] text-zinc-600 mt-2 font-bold uppercase">
                    {{ number_format($stats->total_programmed) }} aves totales
                </p>
            </div>

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Aves Aprobadas</p>
                <p class="text-3xl font-black text-emerald-400 leading-none">{{ number_format($stats->total_approved) }}</p>
                <p class="text-[9px] text-zinc-600 mt-2 font-bold uppercase flex items-center gap-1">
                    <span class="text-yellow-400">{{ number_format($stats->total_under_review) }}</span> en revisión
                </p>
            </div>

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">Documentos / Reclamos</p>
                <div class="flex items-end gap-3 mt-1">
                    <div>
                        <p class="text-2xl font-black text-white leading-none">{{ $documentsCount }}</p>
                        <p class="text-[8px] text-zinc-700 font-black uppercase">Docs</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div>
                        <p class="text-2xl font-black {{ $claimsCount > 0 ? 'text-red-400' : 'text-zinc-600' }} leading-none">{{ $claimsCount }}</p>
                        <p class="text-[8px] text-zinc-700 font-black uppercase">Reclamos</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- DATOS DEL PROVEEDOR --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">01</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Ficha del Tercero</h3>
                </div>
                <div class="p-5 space-y-4">
                    @php
                        $typeLabels = ['poultry' => 'Proveedor de Aves', 'expense' => 'Proveedor de Gastos', 'general' => 'General'];
                        $fields = [
                            ['label' => 'Tipo de Tercero', 'value' => $typeLabels[$provider->provider_type ?? 'general'] ?? 'General'],
                            ['label' => 'Razón Social',   'value' => $provider->business_name],
                            ['label' => 'Nombre Comercial', 'value' => $provider->trade_name ?: '—'],
                            ['label' => 'Identificación',  'value' => $provider->tax_id_type . ' ' . $provider->tax_id],
                            ['label' => 'Teléfono',        'value' => $provider->phone ?: '—'],
                            ['label' => 'Correo',          'value' => $provider->email ?: '—'],
                            ['label' => 'Dirección',       'value' => $provider->address_line ?: '—'],
                            ['label' => 'Ciudad',          'value' => collect([$provider->city, $provider->department])->filter()->implode(', ') ?: '—'],
                            ['label' => 'Plazo Crédito',   'value' => ($provider->payment_terms_days ?? 0) . ' días'],
                            ['label' => 'Método Pago',     'value' => match($provider->preferred_payment_method) {
                                'transfer' => 'Transferencia',
                                'cash' => 'Efectivo',
                                'check' => 'Cheque',
                                default => $provider->preferred_payment_method,
                            }],
                        ];
                    @endphp

                    @foreach($fields as $field)
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">{{ $field['label'] }}</p>
                            <p class="text-xs font-bold text-zinc-300 mt-0.5 uppercase">{{ $field['value'] }}</p>
                        </div>
                    @endforeach

                    <div class="pt-3 border-t border-white/5">
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Registro</p>
                        <p class="text-[10px] text-zinc-500 mt-0.5">{{ $provider->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- DESGLOSE OPERACIÓN + TENDENCIA MENSUAL --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Operación --}}
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-2">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">02</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Desglose Operativo</h3>
                    </div>
                    <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $ops = [
                                ['label' => 'Programadas', 'val' => $stats->total_programmed, 'color' => 'text-zinc-300'],
                                ['label' => 'Aprobadas',   'val' => $stats->total_approved,   'color' => 'text-emerald-400'],
                                ['label' => 'Despachadas', 'val' => $stats->total_dispatched_qty ?? 0, 'color' => 'text-purple-400'],
                                ['label' => 'Canceladas',  'val' => $stats->total_cancelled_qty ?? 0, 'color' => 'text-zinc-600'],
                            ];
                        @endphp
                        @foreach($ops as $op)
                            <div class="text-center">
                                <p class="text-2xl font-black {{ $op['color'] }}">{{ number_format($op['val']) }}</p>
                                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mt-1">{{ $op['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tendencia últimos 6 meses --}}
                @if($monthlyBirds->count())
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-2">
                        <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">03</span>
                        <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Tendencia Mensual (Aves)</h3>
                    </div>
                    <div class="p-5">
                        @php $maxBirds = $monthlyBirds->max('total') ?: 1; @endphp
                        <div class="flex items-end gap-3 h-20">
                            @foreach($monthlyBirds as $mb)
                                @php $pct = round(($mb->total / $maxBirds) * 100); @endphp
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <span class="text-[8px] font-mono text-zinc-700">{{ number_format($mb->total) }}</span>
                                    <div class="w-full bg-black/40 rounded-sm overflow-hidden" style="height: 48px">
                                        <div class="w-full bg-yellow-500/60 rounded-sm transition-all" style="height: {{ $pct }}%; margin-top: {{ 100 - $pct }}%"></div>
                                    </div>
                                    <span class="text-[8px] font-black uppercase text-zinc-600">{{ \Carbon\Carbon::create($mb->year, $mb->month)->translatedFormat('M') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- PEDIDOS RECIENTES --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">04</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Pedidos Recientes</h3>
                </div>
                <a href="{{ route('poultry.orders.index') }}" class="text-[9px] font-black uppercase tracking-widest text-zinc-500 hover:text-yellow-500 transition-colors">
                    Ver todos <i class="fas fa-chevron-right text-[8px] ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/[0.04] text-[9px] font-black uppercase tracking-widest text-zinc-600">
                            <th class="px-6 py-3">Ref</th>
                            <th class="px-4 py-3">Fecha Despacho</th>
                            <th class="px-4 py-3 text-center">Tipo Ave</th>
                            <th class="px-4 py-3 text-right">Cantidad</th>
                            <th class="px-4 py-3 text-center">Aprobación</th>
                            <th class="px-4 py-3 text-center">Operación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-white/[0.01] transition-colors">
                                <td class="px-6 py-3">
                                    <span class="font-mono text-[10px] text-zinc-400">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-bold text-zinc-300">{{ $order->dispatch_date->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-lg border
                                        {{ strtolower($order->poultry_type ?? '') == 'bb'
                                            ? 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20'
                                            : 'bg-orange-500/10 text-orange-400 border-orange-500/20' }}">
                                        {{ strtoupper($order->poultry_type ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-xs font-black text-white font-mono">{{ number_format($order->quantity) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full border
                                        {{ match($order->approval_status) {
                                            'approved'     => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                                            'under_review' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20',
                                            'rejected'     => 'text-red-400 bg-red-500/10 border-red-500/20',
                                            default        => 'text-zinc-500 bg-white/5 border-white/10',
                                        } }}">
                                        {{ match($order->approval_status) {
                                            'approved' => 'Aprobado', 'under_review' => 'Revisión',
                                            'rejected' => 'Rechazado', default => 'Pendiente',
                                        } }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full border
                                        {{ match($order->status) {
                                            'planned'    => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
                                            'dispatched' => 'text-purple-400 bg-purple-500/10 border-purple-500/20',
                                            'paid'       => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                                            'cancelled'  => 'text-zinc-500 bg-white/5 border-white/10',
                                            default      => 'text-zinc-500 bg-white/5 border-white/10',
                                        } }}">
                                        {{ match($order->status) {
                                            'planned' => 'Programado', 'dispatched' => 'Despachado',
                                            'paid' => 'Pagado', 'cancelled' => 'Cancelado',
                                            default => ucfirst($order->status),
                                        } }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-zinc-700">Sin pedidos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
