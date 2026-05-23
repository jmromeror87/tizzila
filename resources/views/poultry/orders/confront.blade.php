{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    @php
        $isLocked = !in_array($order->approval_status, ['pending', 'under_review']);
        $currentBatch = $document ? $document->batches->firstWhere('delivery_date', $order->delivery_date) : null;
    @endphp

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        @keyframes pulse-border {
            0%,100% { border-color: rgba(6,182,212,0.1); }
            50% { border-color: rgba(6,182,212,0.4); }
        }
        .ia-active-card { animation: pulse-border 3s infinite; }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('poultry.orders.index') }}"
                   class="h-10 w-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-zinc-400 hover:text-yellow-500 transition-all">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-yellow-500 uppercase tracking-[0.4em] leading-none mb-0.5">Protocolo de Confrontación v4.0</p>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Análisis <span class="text-yellow-500">VS</span>
                    </h2>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-[#0d121f] border border-white/5 rounded-xl px-4 py-2 text-center">
                    <p class="text-[8px] font-black text-zinc-500 uppercase tracking-widest mb-1">
                        {{ $isLocked ? 'Registro Bloqueado' : 'Status Auditoría' }}
                    </p>
                    <div @class([
                        'text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-lg border flex items-center justify-center gap-2',
                        'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' => $order->approval_status === 'approved',
                        'text-red-400 bg-red-500/10 border-red-500/20' => $order->approval_status === 'rejected',
                        'text-yellow-400 bg-yellow-500/10 border-yellow-500/20' => !$isLocked,
                    ])>
                        @if(!$isLocked)
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span>
                            </span>
                        @else
                            <i class="fas fa-lock text-xs"></i>
                        @endif
                        {{ str_replace('_', ' ', $order->approval_status) }}
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl text-[10px] font-black uppercase tracking-widest">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- INFO PROVEEDOR --}}
        <div class="flex flex-wrap gap-3">
            <div class="bg-[#0d121f] border border-white/5 rounded-xl px-5 py-3">
                <p class="text-[8px] text-zinc-500 font-black uppercase tracking-widest mb-0.5">Entidad Proveedora</p>
                <p class="text-sm font-black text-white uppercase">{{ $order->provider->business_name }}</p>
            </div>
            <div class="bg-[#0d121f] border border-cyan-500/20 rounded-xl px-5 py-3">
                <p class="text-[8px] text-cyan-500 font-black uppercase tracking-widest mb-0.5">Línea Genética</p>
                <p class="text-sm font-black text-white uppercase">{{ $order->poultryType?->icon }} {{ $order->poultryType?->name }}</p>
            </div>
        </div>

        {{-- MÉTRICAS COMPARATIVAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-3">Carga Programada</p>
                <div class="text-5xl font-black text-white tracking-tighter">{{ number_format($result['summary']['programmed']) }}</div>
            </div>

            <div @class([
                'bg-[#0d121f] rounded-2xl p-6 border-2',
                'border-cyan-500/30 ia-active-card' => !$isLocked,
                'border-white/5' => $isLocked
            ])>
                <div class="flex justify-between items-start mb-3">
                    <p class="text-[9px] font-black {{ $isLocked ? 'text-zinc-600' : 'text-cyan-400' }} uppercase tracking-widest">Detección Óptica</p>
                    <div class="flex items-center gap-1.5">
                        @if(!$isLocked)<span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>@endif
                        <span class="text-[8px] font-black {{ $isLocked ? 'text-zinc-600' : 'text-cyan-500' }} uppercase">IA Vision Active</span>
                    </div>
                </div>
                <div class="text-5xl font-black {{ $isLocked ? 'text-zinc-400' : 'text-white' }} tracking-tighter">{{ number_format($result['summary']['approved']) }}</div>
                @if($document)
                    <button onclick="openDocumentModal()" class="mt-4 flex items-center gap-2 text-[10px] font-black text-cyan-400 hover:text-white transition-all uppercase tracking-widest">
                        <i class="fas fa-eye text-xs"></i> Analizar Evidencia
                    </button>
                @endif
            </div>

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-3">Diferencia Neta</p>
                @php $diff = $result['summary']['approved'] - $result['summary']['programmed']; @endphp
                <div @class([
                    'text-5xl font-black tracking-tighter',
                    'text-emerald-400' => $diff >= 0,
                    'text-red-400' => $diff < 0,
                ])>{{ ($diff > 0 ? '+' : '') . number_format($diff) }}</div>
            </div>
        </div>

        {{-- COSTOS Y BIOMETRÍA --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-white uppercase tracking-widest mb-5 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-yellow-500"></i> Desglose Unitario
                </h3>
                <div class="space-y-3">
                    @foreach([
                        ['Costo Ave',         $extras['pricing']['unit_cost']    ?? 0, 'text-white'],
                        ['Fondo FONAV',        $extras['pricing']['fonav_cost']   ?? 0, 'text-cyan-400'],
                        ['Protocolo Vacunas',  $extras['pricing']['vaccine_cost'] ?? 0, 'text-purple-400'],
                    ] as $price)
                        <div class="flex justify-between items-center px-4 py-3 bg-black/30 rounded-xl border border-white/5">
                            <span class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">{{ $price[0] }}</span>
                            <span class="text-lg font-black {{ $price[2] }} font-mono">$ {{ number_format($price[1], 0) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-white uppercase tracking-widest mb-5 flex items-center gap-2">
                    <i class="fas fa-shield-virus text-emerald-400"></i> Certificación Sanitaria
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['marek', 'gumboro'] as $vax)
                        <div class="p-4 bg-black/30 rounded-xl border border-white/5 text-center">
                            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-3">{{ $vax }}</p>
                            @if($extras['vaccines'][$vax] ?? false)
                                <span class="text-[10px] font-black text-emerald-400 bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20">Certificado</span>
                            @else
                                <span class="text-[10px] font-black text-red-400 bg-red-500/10 px-3 py-1.5 rounded-lg border border-red-500/20">Pendiente</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- AJUSTE DE LOTE ACTUAL --}}
        @if($currentBatch)
        <div class="bg-[#0d121f] border {{ $isLocked ? 'border-white/5 opacity-80' : 'border-yellow-500/20' }} rounded-2xl p-6">
            <h3 class="text-[10px] font-black text-white uppercase tracking-widest mb-5 flex items-center gap-3">
                <div class="h-6 w-1 {{ $isLocked ? 'bg-zinc-700' : 'bg-yellow-500' }} rounded-full"></div>
                {{ $isLocked ? 'Registro Auditado' : 'Ajuste Maestro de Lote' }}
            </h3>
            <form method="POST" action="{{ route('poultry.batch.update', $currentBatch->id) }}">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 items-end">
                    <div>
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Lote Fecha</p>
                        <p class="text-base font-black text-yellow-500">{{ $currentBatch->delivery_date->format('d/m/Y') }}</p>
                        @if($currentBatch->was_manually_edited)
                            <span class="text-[8px] font-black text-red-400 uppercase">Ajustado Manualmente</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-cyan-500 uppercase tracking-widest mb-1">IA Det.</p>
                        <div class="text-2xl font-black text-white">{{ number_format($currentBatch->quantity) }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-yellow-500 uppercase tracking-widest mb-1.5">Verificación Humana</label>
                        <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="verified_quantity"
                            value="{{ $currentBatch->verified_quantity ?? $currentBatch->quantity }}"
                            {{ $isLocked ? 'disabled' : '' }}
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-lg font-black px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Motivo de Ajuste</label>
                        <div class="flex gap-2">
                            <input type="text" name="reason" required
                                value="{{ $currentBatch->edit_reason ?? '' }}"
                                placeholder="{{ $isLocked ? 'Sin observaciones' : 'Ej: Error lectura guía' }}"
                                {{ $isLocked ? 'disabled' : '' }}
                                class="flex-1 bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                            @if(!$isLocked)
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-black px-4 rounded-xl font-black transition-all">
                                    <i class="fas fa-save"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- LOTES MÚLTIPLES --}}
        @if(isset($batches) && $batches->count())
        <div class="space-y-4">
            <h3 class="text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-3">
                <div class="h-6 w-1 {{ $isLocked ? 'bg-zinc-700' : 'bg-yellow-500' }} rounded-full"></div>
                {{ $isLocked ? 'Historial de Lotes (Auditado)' : 'Ajuste Maestro de Lotes' }}
            </h3>
            @foreach($batches as $batch)
            <div class="bg-[#0d121f] border {{ $isLocked ? 'border-white/5 opacity-80' : 'border-yellow-500/10 hover:border-yellow-500/30' }} rounded-2xl p-6 transition-all">
                <form method="POST" action="{{ route('poultry.batch.update', $batch->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 items-end">
                        <div>
                            <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1">Lote Fecha</p>
                            <p class="text-base font-black text-yellow-500">{{ $batch->delivery_date->format('d/m/Y') }}</p>
                            @if($batch->was_manually_edited)
                                <span class="text-[8px] font-black text-red-400 uppercase">Ajustado Manualmente</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-cyan-500 uppercase tracking-widest mb-1">IA Det.</p>
                            <div class="text-2xl font-black text-white">{{ number_format($batch->quantity) }}</div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-yellow-500 uppercase tracking-widest mb-1.5">Verificación Humana</label>
                            <input type="number" style="appearance:none;-moz-appearance:textfield;-webkit-appearance:none" name="verified_quantity"
                                value="{{ $batch->verified_quantity ?? $batch->quantity }}"
                                {{ $isLocked ? 'disabled' : '' }}
                                class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-lg font-black px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-1.5">Motivo de Ajuste</label>
                            <div class="flex gap-2">
                                <input type="text" name="reason" required
                                    value="{{ $batch->edit_reason ?? '' }}"
                                    placeholder="{{ $isLocked ? 'Sin observaciones' : 'Ej: Error lectura guía' }}"
                                    {{ $isLocked ? 'disabled' : '' }}
                                    class="flex-1 bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                @if(!$isLocked)
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-black px-4 rounded-xl font-black transition-all">
                                        <i class="fas fa-save"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ACCIONES CRÍTICAS --}}
        <div class="pt-2">
            @if(!$isLocked)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <form method="POST" action="{{ route('poultry.orders.confirm', $order) }}" class="md:col-span-3">
                        @csrf
                        <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-500 py-5 rounded-2xl font-black uppercase tracking-widest text-white shadow-[0_8px_20px_rgba(16,185,129,0.2)] transition-all flex items-center justify-center gap-3">
                            <i class="fas fa-shield-check text-lg"></i> Validar Recepción y Cerrar Expediente
                        </button>
                    </form>
                    <form method="POST" action="{{ route('poultry.orders.incident', $order) }}">
                        @csrf
                        <button type="submit"
                            class="w-full h-full bg-black/40 border border-red-500/30 text-red-400 py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">
                            Reportar Incidente
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-[#0d121f] border border-emerald-500/20 rounded-2xl p-10 text-center">
                    <div class="inline-flex h-16 w-16 bg-emerald-500/10 text-emerald-400 rounded-2xl items-center justify-center text-2xl mb-4 border border-emerald-500/20">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white uppercase tracking-tighter leading-none mb-2">Expediente Auditado</h3>
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">
                        Certificado por: <span class="text-emerald-400">{{ $order->approvedBy?->name ?? 'Sistema Central' }}</span>
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL EVIDENCIA --}}
    @if($document)
    <div id="documentModal" class="fixed inset-0 z-[100] hidden bg-black/95 backdrop-blur-xl items-center justify-center p-6">
        <div class="relative w-full max-w-5xl bg-[#0d121f] border border-white/10 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Asset ID: {{ $document->id }}</h3>
                <button onclick="closeDocumentModal()" class="h-9 w-9 bg-white/5 rounded-xl hover:bg-red-500 transition-all text-white">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="p-4 bg-black flex justify-center overflow-auto max-h-[70vh]">
                <img src="{{ asset('storage/' . str_replace('public/', '', $document->file_path)) }}"
                     class="rounded-xl max-w-full h-auto shadow-2xl">
            </div>
            <div class="px-6 py-4 bg-black/40 flex justify-end">
                <a href="{{ asset('storage/' . str_replace('public/', '', $document->file_path)) }}"
                   target="_blank"
                   class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest hover:text-yellow-500 transition-all">
                    Abrir Original
                </a>
            </div>
        </div>
    </div>
    @endif

    <script>
        function openDocumentModal()  { document.getElementById('documentModal').classList.replace('hidden','flex'); }
        function closeDocumentModal() { document.getElementById('documentModal').classList.replace('flex','hidden'); }
    </script>
</x-app-layout>
