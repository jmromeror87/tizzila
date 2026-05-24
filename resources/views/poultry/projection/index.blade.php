{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Comercial · Planeación</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Proyección <span class="text-yellow-500">3 Meses</span>
                </h2>
            </div>
            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
    </x-slot>

    <div class="py-4 space-y-6">

        {{-- ══ ALERTAS DE MERCADO ACTIVAS ══ --}}
        @if(isset($marketEvents) && $marketEvents->count() > 0)
        <div class="space-y-2">
            @foreach($marketEvents as $me)
            @php
                $mc = match($me->severity) { 'high' => 'red', 'medium' => 'yellow', default => 'blue' };
            @endphp
            <div class="bg-{{ $mc }}-500/10 border border-{{ $mc }}-500/30 rounded-2xl px-5 py-3 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-triangle-exclamation text-{{ $mc }}-400"></i>
                    <div>
                        <p class="text-[10px] font-black text-{{ $mc }}-300">{{ $me->title }}</p>
                        <p class="text-[9px] text-zinc-500">{{ $me->type_label }} · Impacto estimado: {{ $me->estimated_impact_pct }}% en ventas</p>
                    </div>
                </div>
                <a href="{{ route('market-events.index') }}"
                   class="text-[9px] font-black text-{{ $mc }}-400 uppercase tracking-widest hover:text-{{ $mc }}-300 transition-colors flex-shrink-0">
                    Ver →
                </a>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ══ PANEL IA ══ --}}
        @php
            $tendenciaColor = match($aiAnalysis['tendencia'] ?? 'estable') {
                'creciendo'  => 'emerald',
                'declinando' => 'red',
                default      => 'yellow',
            };
            $tendenciaIcon = match($aiAnalysis['tendencia'] ?? 'estable') {
                'creciendo'  => 'fa-arrow-trend-up',
                'declinando' => 'fa-arrow-trend-down',
                default      => 'fa-minus',
            };
            $alertaColor = match($aiAnalysis['alerta_tipo'] ?? null) {
                'riesgo'      => 'red',
                'oportunidad' => 'emerald',
                default       => 'blue',
            };
        @endphp

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center">
                        <i class="fas fa-brain text-yellow-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.3em]">Análisis Predictivo · GPT-4o</p>
                        <p class="text-[10px] font-black text-white">Motor de Inteligencia Comercial</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 bg-{{ $tendenciaColor }}-500/10 border border-{{ $tendenciaColor }}-500/20 px-3 py-1.5 rounded-xl">
                    <i class="fas {{ $tendenciaIcon }} text-{{ $tendenciaColor }}-400 text-xs"></i>
                    <span class="text-[9px] font-black text-{{ $tendenciaColor }}-400 uppercase tracking-widest">
                        {{ ucfirst($aiAnalysis['tendencia'] ?? 'estable') }}
                        @if(($aiAnalysis['tendencia_pct'] ?? 0) > 0)
                            {{ $aiAnalysis['tendencia_pct'] }}%
                        @endif
                    </span>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    <p class="text-sm text-zinc-300 leading-relaxed">{{ $aiAnalysis['resumen'] ?? '' }}</p>

                    @if(!empty($aiAnalysis['alerta']))
                    <div class="bg-{{ $alertaColor }}-500/10 border border-{{ $alertaColor }}-500/20 rounded-xl px-4 py-3 flex items-start gap-3">
                        <i class="fas fa-{{ $aiAnalysis['alerta_tipo'] === 'riesgo' ? 'triangle-exclamation' : ($aiAnalysis['alerta_tipo'] === 'oportunidad' ? 'lightbulb' : 'circle-info') }} text-{{ $alertaColor }}-400 text-sm mt-0.5"></i>
                        <p class="text-xs text-{{ $alertaColor }}-300">{{ $aiAnalysis['alerta'] }}</p>
                    </div>
                    @endif

                    @if(!empty($aiAnalysis['recomendaciones']))
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Recomendaciones</p>
                        @foreach($aiAnalysis['recomendaciones'] as $rec)
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-yellow-500/60 text-[10px] mt-0.5 flex-shrink-0"></i>
                            <p class="text-[11px] text-zinc-400">{{ $rec }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Cantidad sugerida por IA</p>
                    @foreach($aiAnalysis['prediccion_meses'] ?? [] as $pred)
                    @php
                        $confColor = match($pred['confianza'] ?? 'baja') {
                            'alta'  => 'emerald',
                            'media' => 'yellow',
                            default => 'zinc',
                        };
                    @endphp
                    <div class="bg-black/30 border border-white/5 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-[9px] font-black text-zinc-500 uppercase">{{ $pred['mes'] }}</p>
                            <span class="text-[8px] font-black text-{{ $confColor }}-400 bg-{{ $confColor }}-500/10 px-2 py-0.5 rounded-full uppercase">
                                {{ $pred['confianza'] }}
                            </span>
                        </div>
                        <p class="text-xl font-black text-white tracking-tighter">{{ number_format($pred['cantidad_sugerida']) }}</p>
                        <p class="text-[9px] text-zinc-600 mt-1">{{ $pred['razon'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══ GRÁFICA HISTÓRICA ══ --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Tendencia histórica</p>
                    <p class="text-[10px] font-black text-white">Últimos 6 meses · Aves despachadas</p>
                </div>
                <div class="flex items-center gap-4 text-[9px] font-black uppercase tracking-widest text-zinc-500">
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-1 bg-yellow-500 rounded"></span> Real</span>
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-1 bg-blue-500/50 rounded"></span> Programado</span>
                </div>
            </div>

            @php
                $allVals = collect($historical)->pluck('total')
                    ->concat(collect($projection)->pluck('total_programmed'))
                    ->concat(collect($projection)->pluck('stat_prediction'));
                $maxVal  = max($allVals->max(), 1);
            @endphp

            <div class="flex items-end gap-2" style="height:160px">
                @foreach($historical as $h)
                @php $pct = round(($h['total'] / $maxVal) * 100); @endphp
                <div class="flex-1 flex flex-col items-center gap-1 group" style="height:100%">
                    <p class="text-[8px] text-zinc-600 group-hover:text-yellow-400 transition-colors font-black leading-none">
                        {{ $h['total'] > 0 ? number_format($h['total'] / 1000, 0).'K' : '-' }}
                    </p>
                    <div class="flex-1 flex items-end w-full">
                        <div class="w-full rounded-t-lg {{ $h['total'] > 0 ? 'bg-yellow-500/70' : 'bg-white/5' }}"
                             style="height:{{ max(2, $pct) }}%"></div>
                    </div>
                    <p class="text-[8px] text-zinc-600 font-black uppercase leading-none">{{ $h['month'] }}</p>
                </div>
                @endforeach

                <div class="w-px bg-white/10 self-stretch mx-1"></div>

                @foreach($projection as $i => $p)
                @php
                    $val    = $p['total_programmed'] ?: $p['stat_prediction'];
                    $pct    = round(($val / $maxVal) * 100);
                    $aiPred = collect($aiAnalysis['prediccion_meses'] ?? [])->values()->get($i);
                    $aiPct  = $aiPred ? round(($aiPred['cantidad_sugerida'] / $maxVal) * 100) : 0;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1 group" style="height:100%">
                    <p class="text-[8px] text-blue-400 font-black leading-none">
                        {{ $val > 0 ? number_format($val / 1000, 0).'K' : '~' }}
                    </p>
                    <div class="flex-1 flex items-end w-full relative">
                        <div class="w-full rounded-t-lg {{ $p['has_data'] ? 'bg-blue-500/50' : 'bg-blue-500/10 border border-blue-500/20' }}"
                             style="height:{{ max(2, $pct) }}%"></div>
                    </div>
                    <p class="text-[8px] text-blue-400/70 font-black uppercase leading-none">
                        {{ Str::words($p['month'], 1, '') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══ TARJETAS 3 MESES ══ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @foreach($projection as $i => $m)
            @php
                $aiPred    = collect($aiAnalysis['prediccion_meses'] ?? [])->values()->get($i);
                $aiQty     = $aiPred['cantidad_sugerida'] ?? null;
                $aiConf    = $aiPred['confianza'] ?? 'baja';
                $confColor = match($aiConf) { 'alta' => 'emerald', 'media' => 'yellow', default => 'zinc' };
                $diffVsAI  = ($aiQty && $m['total_programmed'] > 0) ? ($m['total_programmed'] - $aiQty) : null;
                $base      = max($m['total_programmed'], $m['stat_prediction'], 1);
                $fixedPct  = min(100, round(($m['fixed_committed'] / $base) * 100));
                $varPct    = 100 - $fixedPct;
            @endphp
            <div class="bg-[#0d121f] border border-{{ $m['is_current'] ? 'yellow-500/20' : 'white/5' }} rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                    <div>
                        <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest">{{ $m['month_label'] }}</p>
                        <p class="text-base font-black text-white uppercase tracking-tight">{{ $m['month'] }}</p>
                    </div>
                    <span class="text-[8px] font-black text-zinc-500 bg-white/5 px-2 py-1 rounded-lg">{{ $m['orders_count'] }} pedidos</span>
                </div>

                <div class="p-5 space-y-4">
                    <div class="text-center">
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Total Programado</p>
                        <p class="text-3xl font-black text-white tracking-tighter">
                            {{ $m['total_programmed'] > 0 ? number_format($m['total_programmed']) : '—' }}
                        </p>
                        @if($aiQty)
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <p class="text-[9px] text-zinc-600">IA sugiere:</p>
                            <p class="text-[9px] font-black text-{{ $confColor }}-400">{{ number_format($aiQty) }}</p>
                            @if($diffVsAI !== null)
                            <span class="text-[8px] font-black {{ $diffVsAI >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                ({{ $diffVsAI >= 0 ? '+' : '' }}{{ number_format($diffVsAI) }})
                            </span>
                            @endif
                        </div>
                        @elseif(!$m['has_data'])
                        <p class="text-[9px] text-zinc-600 mt-1">Estadística: {{ number_format($m['stat_prediction']) }} aves</p>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between text-[8px] font-black mb-1">
                            <span class="text-yellow-400">FIJO {{ $fixedPct }}%</span>
                            <span class="text-blue-400">VARIABLE {{ $varPct }}%</span>
                        </div>
                        <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden flex">
                            <div class="h-full bg-yellow-500 transition-all" style="width:{{ $fixedPct }}%"></div>
                            <div class="h-full bg-blue-500/50 transition-all" style="width:{{ $varPct }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-black/30 rounded-xl p-3 text-center">
                            <p class="text-[8px] text-zinc-600 uppercase font-black">Fijos</p>
                            <p class="text-sm font-black text-yellow-400">{{ number_format($m['fixed_committed']) }}</p>
                            <p class="text-[8px] text-zinc-700">{{ $m['fixed_count'] }} clientes</p>
                        </div>
                        <div class="bg-black/30 rounded-xl p-3 text-center">
                            <p class="text-[8px] text-zinc-600 uppercase font-black">Variable</p>
                            <p class="text-sm font-black text-blue-400">{{ number_format($m['variable_available']) }}</p>
                            <p class="text-[8px] text-zinc-700">por asignar</p>
                        </div>
                    </div>

                    @if(!empty($m['by_type']))
                    <div class="space-y-1.5 pt-1 border-t border-white/5">
                        @foreach($m['by_type'] as $tipo => $qty)
                        <div class="flex justify-between items-center">
                            <p class="text-[9px] text-zinc-600 uppercase font-black">{{ $tipo }}</p>
                            <p class="text-[9px] font-black text-zinc-400">{{ number_format($qty) }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- ══ CLIENTES FIJOS ══ --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.3em]">Clientes Fijos Comprometidos</p>
                    <p class="text-[9px] text-zinc-600">Demanda garantizada cada semana</p>
                </div>
                <a href="{{ route('customers.index') }}"
                   class="text-[9px] font-black text-yellow-400 hover:text-yellow-300 uppercase tracking-widest transition-colors">
                    Gestionar →
                </a>
            </div>

            @if($fixedClients->isEmpty())
            <div class="py-14 flex flex-col items-center gap-3 text-zinc-700">
                <i class="fas fa-star text-4xl opacity-20"></i>
                <p class="text-[9px] font-black uppercase tracking-widest">Sin clientes fijos configurados</p>
                <p class="text-[9px] text-zinc-700">Marca clientes como "fijo" en su perfil para verlos aquí</p>
            </div>
            @else
            <div class="divide-y divide-white/[0.04]">
                @foreach($fixedClients as $fc)
                @php $effColor = $fc->effectiveness >= 90 ? 'emerald' : ($fc->effectiveness >= 70 ? 'yellow' : 'red'); @endphp
                <div class="px-6 py-3 flex items-center gap-4 hover:bg-white/[0.02] transition-colors">
                    <div class="flex-1">
                        <p class="text-xs font-black text-white">{{ $fc->name }}</p>
                        <p class="text-[9px] text-zinc-600">{{ number_format($fc->fixed_weekly_quantity) }} aves/sem</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-{{ $effColor }}-400">{{ $fc->effectiveness }}% efectividad</p>
                        @if(($fc->total_balance ?? 0) > 0)
                        <p class="text-[9px] text-red-400">${{ number_format($fc->total_balance) }} deuda activa</p>
                        @endif
                    </div>
                    <div class="w-16 h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $effColor }}-500 rounded-full" style="width:{{ $fc->effectiveness }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-3 border-t border-white/5 flex justify-between items-center bg-black/20">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Total comprometido / semana</p>
                <p class="text-sm font-black text-yellow-400">{{ number_format($fixedClients->sum('fixed_weekly_quantity')) }} aves</p>
            </div>
            @endif
        </div>

    </div>
</x-app-layout>
