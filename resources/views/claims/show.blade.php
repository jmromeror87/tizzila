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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('claims.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Reclamo <span class="text-yellow-500">#{{ str_pad($claim->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                        {{ $claim->provider->business_name ?? 'Sin proveedor' }} ·
                        Actualizado {{ $claim->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            @php
                $headerStatus = match($claim->status) {
                    'pending'   => ['text-amber-400 bg-amber-500/10 border-amber-500/20', 'Pendiente'],
                    'submitted' => ['text-blue-400 bg-blue-500/10 border-blue-500/20',   'Enviado a Revisión'],
                    'approved'  => ['text-emerald-400 bg-emerald-500/10 border-emerald-500/20', 'Aprobado'],
                    'rejected'  => ['text-red-400 bg-red-500/10 border-red-500/20',      'Rechazado'],
                    'credited'  => ['text-purple-400 bg-purple-500/10 border-purple-500/20', 'Nota de Crédito Emitida'],
                    default     => ['text-zinc-500 bg-white/5 border-white/10',           ucfirst($claim->status)],
                };
            @endphp
            <span class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border {{ $headerStatus[0] }}">
                {{ $headerStatus[1] }}
            </span>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- MÉTRICAS --}}
        @php
            $metrics = [
                ['label' => 'Programado',      'val' => number_format($claim->scheduled_quantity), 'sub' => 'Unidades', 'color' => 'text-white'],
                ['label' => 'Recibido',         'val' => number_format($claim->received_quantity),  'sub' => 'Confirmado', 'color' => 'text-blue-400'],
                ['label' => 'Bajas (Dead)',     'val' => number_format($claim->dead_quantity),       'sub' => 'Pérdida crítica', 'color' => 'text-red-400'],
                ['label' => 'Monto Reclamado',  'val' => '$' . number_format($claim->claim_amount, 0), 'sub' => 'Valorización', 'color' => 'text-yellow-500'],
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($metrics as $metric)
                <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600 mb-1">{{ $metric['label'] }}</p>
                    <p class="text-2xl font-black {{ $metric['color'] }}">{{ $metric['val'] }}</p>
                    <p class="text-[9px] text-zinc-700 font-bold uppercase mt-1">{{ $metric['sub'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- INFORMACIÓN DEL PROVEEDOR --}}
            <div class="lg:col-span-2 bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">01</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Información del Proveedor</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Razón Social</p>
                        <p class="text-sm font-black text-white uppercase mt-0.5">{{ $claim->provider->business_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Identificación Fiscal</p>
                        <p class="text-sm font-mono text-zinc-300 mt-0.5">{{ $claim->provider->tax_id ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Teléfono</p>
                        <p class="text-sm text-zinc-300 mt-0.5 flex items-center gap-2">
                            <i class="fas fa-phone text-zinc-600 text-[10px]"></i>
                            {{ $claim->provider->phone ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Correo</p>
                        <p class="text-sm text-zinc-300 mt-0.5 flex items-center gap-2">
                            <i class="fas fa-envelope text-zinc-600 text-[10px]"></i>
                            {{ $claim->provider->email ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Ruta / Confirmación</p>
                        <p class="text-sm font-mono text-zinc-300 mt-0.5">
                            Ruta #{{ $claim->confirmation->dispatch_route_id ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-600">Precio Unitario</p>
                        <p class="text-sm font-black text-yellow-500 mt-0.5">
                            ${{ number_format($claim->unit_price ?? 0, 2) }}/u
                        </p>
                    </div>
                </div>
            </div>

            {{-- ESTADO + FECHAS --}}
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">02</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Estado del Proceso</h3>
                </div>
                <div class="p-6 flex flex-col items-center justify-center text-center gap-4">
                    @php
                        $statusStyle = match($claim->status) {
                            'pending'   => ['text-amber-400 bg-amber-500/10 border-amber-500/20', 'Pendiente',           'fa-clock'],
                            'submitted' => ['text-blue-400 bg-blue-500/10 border-blue-500/20',   'Enviado a Revisión',  'fa-paper-plane'],
                            'approved'  => ['text-emerald-400 bg-emerald-500/10 border-emerald-500/20', 'Aprobado',    'fa-check-double'],
                            'rejected'  => ['text-red-400 bg-red-500/10 border-red-500/20',      'Rechazado',           'fa-times-circle'],
                            'credited'  => ['text-purple-400 bg-purple-500/10 border-purple-500/20', 'Nota Crédito',    'fa-file-invoice-dollar'],
                            default     => ['text-zinc-500 bg-white/5 border-white/10',           ucfirst($claim->status), 'fa-question'],
                        };
                    @endphp

                    <div class="h-16 w-16 rounded-2xl border {{ $statusStyle[0] }} flex items-center justify-center">
                        <i class="fas {{ $statusStyle[2] }} text-2xl"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border {{ $statusStyle[0] }}">
                        {{ $statusStyle[1] }}
                    </span>

                    <div class="w-full space-y-2 mt-2 text-left">
                        <div class="flex justify-between">
                            <span class="text-[9px] text-zinc-600 font-black uppercase">Creado</span>
                            <span class="text-[9px] text-zinc-400 font-bold">{{ $claim->created_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        @if($claim->submitted_at)
                        <div class="flex justify-between">
                            <span class="text-[9px] text-zinc-600 font-black uppercase">Enviado</span>
                            <span class="text-[9px] text-blue-400 font-bold">{{ $claim->submitted_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        @endif
                        @if($claim->resolved_at)
                        <div class="flex justify-between">
                            <span class="text-[9px] text-zinc-600 font-black uppercase">Resuelto</span>
                            <span class="text-[9px] text-emerald-400 font-bold">{{ $claim->resolved_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- EVIDENCIA FOTOGRÁFICA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">03</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Evidencia de Campo</h3>
                </div>
                <span class="text-[9px] text-zinc-500 font-black">{{ $claim->evidences->count() }} archivo(s)</span>
            </div>
            <div class="p-6">
                @if($claim->evidences->count())
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach($claim->evidences as $photo)
                            <a href="{{ Storage::url($photo->image_path) }}" target="_blank"
                               class="relative aspect-square rounded-xl overflow-hidden border border-white/5 hover:border-yellow-500/40 transition-all group">
                                <img src="{{ Storage::url($photo->image_path) }}" class="object-cover w-full h-full grayscale group-hover:grayscale-0 transition-all duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                                    <span class="text-[8px] font-black text-white uppercase tracking-widest">Ver</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 flex flex-col items-center opacity-20">
                        <i class="fas fa-camera text-3xl text-zinc-600 mb-2"></i>
                        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-600">Sin registro fotográfico</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- OBSERVACIONES + PANEL DE CONTROL --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">04</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Observaciones del Analista</h3>
                </div>
                <div class="p-6">
                    <div class="bg-black/30 border border-white/5 rounded-xl p-4 min-h-[80px]">
                        <p class="text-sm text-zinc-400 leading-relaxed">
                            {{ $claim->notes ?? 'Sin observaciones adicionales registradas.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-[#0d121f] border border-yellow-500/20 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-yellow-500/10 flex items-center gap-2">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">05</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-yellow-500">Panel de Operaciones</h3>
                </div>
                <div class="p-6 flex flex-wrap gap-3">

                    @if($claim->status == 'pending')
                        <form method="POST" action="{{ route('claims.submit', $claim->id) }}" class="w-full">
                            @csrf
                            <button class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i> Enviar a Revisión
                            </button>
                        </form>
                    @endif

                    @if($claim->status == 'submitted')
                        <form method="POST" action="{{ route('claims.approve', $claim->id) }}" class="flex-1">
                            @csrf
                            <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('claims.reject', $claim->id) }}" class="flex-1">
                            @csrf
                            <button class="w-full bg-red-600 hover:bg-red-500 text-white font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-times-circle"></i> Rechazar
                            </button>
                        </form>
                        <a href="{{ route('claims.pdf', $claim->id) }}" target="_blank"
                           class="w-full bg-white/5 border border-white/10 hover:bg-yellow-500/10 hover:border-yellow-500/20 hover:text-yellow-400 text-zinc-400 font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-file-pdf"></i> Descargar PDF
                        </a>
                    @endif

                    @if($claim->status == 'approved')
                        <form method="POST" action="{{ route('claims.credited', $claim->id) }}" class="w-full">
                            @csrf
                            <button class="w-full bg-purple-600 hover:bg-purple-500 text-white font-black uppercase text-[10px] tracking-widest px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-file-invoice-dollar"></i> Registrar Nota de Crédito
                            </button>
                        </form>
                    @endif

                    @if($claim->status == 'credited' || $claim->status == 'rejected')
                        <div class="w-full text-center py-4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-zinc-600">
                                Este reclamo está cerrado · {{ $claim->resolved_at?->translatedFormat('d M Y') ?? '—' }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</x-app-layout>
