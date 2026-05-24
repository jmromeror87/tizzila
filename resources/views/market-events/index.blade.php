{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em]">Inteligencia Comercial</p>
                <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                    Novedades de <span class="text-yellow-500">Mercado</span>
                </h2>
            </div>
            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3500)"
             class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black px-5 py-3 rounded-2xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- ALERTAS ACTIVAS --}}
        @if($active->count() > 0)
        <div class="space-y-3">
            @foreach($active as $event)
            @php
                $colors = match($event->severity) {
                    'high'   => ['border-red-500/30',    'bg-red-500/10',    'text-red-400',    'bg-red-500/5'],
                    'medium' => ['border-yellow-500/30', 'bg-yellow-500/10', 'text-yellow-400', 'bg-yellow-500/5'],
                    default  => ['border-blue-500/30',   'bg-blue-500/10',   'text-blue-400',   'bg-blue-500/5'],
                };
                $icons = match($event->type) {
                    'competitor_pricing' => 'fa-tag',
                    'feed_quality'       => 'fa-wheat-awn',
                    'strike'             => 'fa-road-barrier',
                    'oversupply'         => 'fa-boxes-stacked',
                    'demand_drop'        => 'fa-arrow-trend-down',
                    default              => 'fa-triangle-exclamation',
                };
                $sevLabel = match($event->severity) { 'high' => 'CRÍTICO', 'medium' => 'MODERADO', default => 'LEVE' };
            @endphp
            <div class="border {{ $colors[0] }} {{ $colors[3] }} rounded-2xl overflow-hidden">
                <div class="px-5 py-4 flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl {{ $colors[1] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas {{ $icons }} {{ $colors[2] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="text-[8px] font-black {{ $colors[2] }} uppercase tracking-widest {{ $colors[1] }} px-2 py-0.5 rounded-full">
                                {{ $sevLabel }}
                            </span>
                            <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest">
                                {{ $event->type_label }}
                            </span>
                            <span class="text-[8px] font-black text-zinc-700">
                                Desde {{ $event->starts_at->translatedFormat('d M Y') }}
                                @if($event->ends_at) · Hasta {{ $event->ends_at->translatedFormat('d M Y') }} @endif
                            </span>
                        </div>
                        <p class="text-sm font-black text-white">{{ $event->title }}</p>
                        @if($event->description)
                        <p class="text-[11px] text-zinc-400 mt-1 leading-relaxed">{{ $event->description }}</p>
                        @endif
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-[9px] font-black text-red-400">
                                Impacto estimado: {{ $event->estimated_impact_pct }}% en ventas
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('market-events.toggle', $event) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="h-8 px-3 bg-white/5 hover:bg-white/10 border border-white/10 text-zinc-500 hover:text-zinc-300 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                Cerrar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('market-events.destroy', $event) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="h-8 w-8 bg-red-500/0 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 text-zinc-700 hover:text-red-400 rounded-xl flex items-center justify-center transition-all">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-emerald-500/5 border border-emerald-500/20 rounded-2xl px-6 py-4 flex items-center gap-3">
            <i class="fas fa-circle-check text-emerald-400"></i>
            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Sin novedades activas · El mercado está estable</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- FORMULARIO NUEVA NOVEDAD --}}
            <div class="lg:col-span-1">
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden sticky top-4">
                    <div class="px-5 py-4 border-b border-white/5">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                            <i class="fas fa-plus text-yellow-500 mr-2"></i>Registrar Novedad
                        </h3>
                    </div>
                    <div class="p-5">
                        <form method="POST" action="{{ route('market-events.store') }}" class="space-y-4">
                            @csrf

                            @if($errors->any())
                            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-black px-4 py-3 rounded-xl">
                                @foreach($errors->all() as $e)<p>· {{ $e }}</p>@endforeach
                            </div>
                            @endif

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Título</label>
                                <input type="text" name="title" value="{{ old('title') }}" required
                                    placeholder="Ej: San Marino regalando el 50% del pollo"
                                    class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 placeholder-zinc-700">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Tipo de Novedad</label>
                                <select name="type" required
                                    class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                    @foreach($typeLabels as $val => $label)
                                    <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Severidad</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach(['low' => ['LEVE', 'blue'], 'medium' => ['MODERADO', 'yellow'], 'high' => ['CRÍTICO', 'red']] as $val => [$lbl, $col])
                                    <label class="cursor-pointer">
                                        <input type="radio" name="severity" value="{{ $val }}" class="sr-only peer"
                                            {{ old('severity', 'medium') === $val ? 'checked' : '' }}>
                                        <div class="text-center py-2 px-1 rounded-xl border border-white/10 text-[9px] font-black uppercase
                                            peer-checked:border-{{ $col }}-500/40 peer-checked:bg-{{ $col }}-500/10 peer-checked:text-{{ $col }}-400
                                            text-zinc-600 transition-all cursor-pointer hover:border-white/20">
                                            {{ $lbl }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">
                                    Impacto estimado en ventas
                                </label>
                                <div class="flex items-center gap-3">
                                    <input type="range" name="estimated_impact_pct" id="impactRange"
                                        min="-100" max="0" step="5" value="{{ old('estimated_impact_pct', -20) }}"
                                        oninput="document.getElementById('impactVal').textContent = this.value + '%'"
                                        class="flex-1 accent-yellow-500">
                                    <span id="impactVal" class="text-sm font-black text-red-400 w-12 text-right">
                                        {{ old('estimated_impact_pct', -20) }}%
                                    </span>
                                </div>
                                <p class="text-[9px] text-zinc-600 mt-1">Cuánto crees que va a bajar tu venta</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Desde</label>
                                    <input type="date" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d')) }}" required
                                        class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Hasta (opcional)</label>
                                    <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Descripción (opcional)</label>
                                <textarea name="description" rows="3"
                                    placeholder="Detalles de la novedad y cómo te afecta..."
                                    class="w-full px-4 py-2.5 rounded-xl bg-black/40 border border-white/10 text-sm text-white outline-none focus:border-yellow-500/50 placeholder-zinc-700 resize-none">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-triangle-exclamation text-xs"></i> Registrar Novedad
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- HISTORIAL --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Qué hace el sistema con esto --}}
                <div class="bg-yellow-500/5 border border-yellow-500/20 rounded-2xl p-5 flex items-start gap-4">
                    <i class="fas fa-brain text-yellow-400 text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-[10px] font-black text-yellow-400 uppercase tracking-widest mb-1">¿Para qué sirve registrar esto?</p>
                        <p class="text-[11px] text-zinc-400 leading-relaxed">
                            Cada novedad activa le avisa a la IA de la proyección que el mercado está afectado.
                            Así la proyección de ventas ajusta las cantidades sugeridas automáticamente y te muestra alertas en el tablero
                            para que actúes antes de que el sobrante llegue.
                        </p>
                    </div>
                </div>

                {{-- Novedades cerradas / historial --}}
                @if($past->count() > 0)
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Historial</h3>
                        <span class="text-[9px] text-zinc-600">{{ $past->count() }} registros</span>
                    </div>
                    <div class="divide-y divide-white/[0.04]">
                        @foreach($past as $event)
                        @php
                            $col = match($event->severity) { 'high' => 'red', 'medium' => 'yellow', default => 'blue' };
                        @endphp
                        <div class="px-5 py-3 flex items-center gap-3 opacity-50 hover:opacity-80 transition-opacity">
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-black text-zinc-400 truncate">{{ $event->title }}</p>
                                <p class="text-[9px] text-zinc-600">
                                    {{ $event->type_label }} · {{ $event->starts_at->translatedFormat('d M Y') }}
                                    @if($event->ends_at) → {{ $event->ends_at->translatedFormat('d M Y') }} @endif
                                </p>
                            </div>
                            <span class="text-[9px] font-black text-{{ $col }}-400/60">{{ $event->estimated_impact_pct }}%</span>
                            <form method="POST" action="{{ route('market-events.toggle', $event) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="Reactivar"
                                    class="h-7 px-3 bg-white/5 hover:bg-yellow-500/10 border border-white/5 hover:border-yellow-500/20 text-zinc-700 hover:text-yellow-400 rounded-lg text-[9px] font-black uppercase transition-all">
                                    Reactivar
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
