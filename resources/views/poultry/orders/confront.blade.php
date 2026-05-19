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
    {{-- Protocolo de Confrontación Tizzilla v2.0 - Security Lockdown --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap');
        
        :root {
            --tz-yellow: #FFCC00;
            --tz-bg: #08080A;
            --tz-card: rgba(18, 18, 22, 0.7);
            --tz-cyan: #06B6D4;
        }

        /* 🚫 ELIMINAR SPINNERS (FLECHITAS) DEFINITIVAMENTE */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .tizzilla-dark { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--tz-bg);
            background-image: radial-gradient(circle at 50% -20%, #1a1a1e 0%, var(--tz-bg) 80%);
        }

        .tizzilla-font-heavy { font-weight: 900; letter-spacing: -0.02em; }

        .glass-card {
            background: var(--tz-card);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.7);
        }

        .input-tizzilla {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            transition: all 0.3s ease;
        }

        .input-tizzilla:focus:not(:disabled) {
            border-color: var(--tz-yellow) !important;
            box-shadow: 0 0 15px rgba(255, 204, 0, 0.1);
            outline: none;
        }

        /* Estilo para campos bloqueados */
        .input-tizzilla:disabled {
            background: rgba(255, 255, 255, 0.02) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
            color: rgba(255, 255, 255, 0.3) !important;
            cursor: not-allowed;
        }

        .neon-text-yellow { text-shadow: 0 0 15px rgba(255, 204, 0, 0.4); }
        .neon-text-cyan { text-shadow: 0 0 15px rgba(6, 182, 212, 0.4); }

        @keyframes pulse-border {
            0% { border-color: rgba(6, 182, 212, 0.1); }
            50% { border-color: rgba(6, 182, 212, 0.4); }
            100% { border-color: rgba(6, 182, 212, 0.1); }
        }
        .ia-active-card { animation: pulse-border 3s infinite; }
    </style>

    @php
        // Definimos si el expediente está cerrado
        $isLocked = !in_array($order->approval_status, ['pending', 'under_review']);
        
        // Filtramos para que SOLO se use el lote que corresponde a la fecha de la orden actual
        $currentBatch = $document ? $document->batches->firstWhere('delivery_date', $order->delivery_date) : null;
    @endphp

    <div class="tizzilla-dark min-h-screen pb-24 text-white antialiased">

        {{-- 1. ALERTAS GLOBALES --}}
        <div class="max-w-7xl mx-auto px-6 pt-6">
            @if(session('success'))
                <div class="flex items-center gap-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-5 rounded-3xl text-[10px] tizzilla-font-heavy uppercase tracking-[0.2em] shadow-2xl animate-bounce">
                    <i class="fas fa-check-circle text-xl"></i>
                    {{ session('success') }}
                </div>
            @endif
        </div>

        {{-- 2. HEADER --}}
        <header class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-8">
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('poultry.orders.index') }}" class="h-14 w-14 rounded-2xl bg-zinc-900 border border-white/5 flex items-center justify-center text-zinc-500 hover:text-yellow-400 transition-all">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <p class="text-[10px] tizzilla-font-heavy text-yellow-500 uppercase tracking-[0.5em] leading-none mb-2">Protocolo de Confrontación v4.0</p>
                            <h1 class="text-6xl tizzilla-font-heavy uppercase tracking-tighter leading-none">
                                ANÁLISIS <span class="text-yellow-400 {{ $isLocked ? '' : 'neon-text-yellow' }}">VS</span>
                            </h1>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <div class="px-6 py-4 bg-zinc-900/50 rounded-3xl border border-white/5">
                            <p class="text-[8px] text-zinc-500 tizzilla-font-heavy uppercase tracking-widest mb-1">Entidad Proveedora</p>
                            <p class="text-sm font-bold text-white uppercase">{{ $order->provider->business_name }}</p>
                        </div>
                        <div class="px-6 py-4 bg-cyan-500/5 rounded-3xl border border-cyan-500/20">
                            <p class="text-[8px] text-cyan-500 tizzilla-font-heavy uppercase tracking-widest mb-1">Línea Genética</p>
                            <p class="text-sm font-bold text-white uppercase">{{ $order->poultryType?->icon }} {{ $order->poultryType?->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card px-10 py-8 rounded-[3rem] text-center min-w-[280px]">
                    <span class="text-[9px] block text-zinc-500 tizzilla-font-heavy tracking-[0.4em] mb-4 uppercase">
                        {{ $isLocked ? 'REGISTRO BLOQUEADO' : 'STATUS AUDITORÍA' }}
                    </span>
                    <div @class([
                        'text-[11px] tizzilla-font-heavy uppercase tracking-[0.3em] px-6 py-3 rounded-2xl border flex items-center justify-center gap-3',
                        'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' => $order->approval_status === 'approved',
                        'text-rose-400 bg-rose-500/10 border-rose-500/20' => $order->approval_status === 'rejected',
                        'text-yellow-400 bg-yellow-500/10 border-yellow-500/20' => !$isLocked,
                    ])>
                        @if(!$isLocked)
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-current"></span>
                            </span>
                        @else
                            <i class="fas fa-lock text-xs"></i>
                        @endif
                        {{ str_replace('_', ' ', $order->approval_status) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-6">
            
            {{-- 3. MÉTRICAS COMPARATIVAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="glass-card rounded-[3rem] p-10 relative group">
                    <p class="text-[10px] tizzilla-font-heavy text-zinc-500 uppercase tracking-[0.3em] mb-4">Carga Programada</p>
                    <div class="text-7xl tizzilla-font-heavy tracking-tighter">{{ number_format($result['summary']['programmed']) }}</div>
                </div>

                <div @class([
                    'glass-card rounded-[3rem] p-10 relative border-2',
                    'border-cyan-500/30 ia-active-card' => !$isLocked,
                    'border-zinc-800' => $isLocked
                ])>
                    <div class="absolute top-6 right-10 flex items-center gap-2">
                        @if(!$isLocked) <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span> @endif
                        <span class="text-[9px] tizzilla-font-heavy {{ $isLocked ? 'text-zinc-600' : 'text-cyan-500' }} uppercase">IA Vision Active</span>
                    </div>
                    <p class="text-[10px] tizzilla-font-heavy {{ $isLocked ? 'text-zinc-600' : 'text-cyan-400' }} uppercase tracking-[0.3em] mb-4">Detección Óptica</p>
                    <div class="text-7xl tizzilla-font-heavy tracking-tighter {{ $isLocked ? 'text-zinc-400' : 'text-white neon-text-cyan' }}">{{ number_format($result['summary']['approved']) }}</div>
                    
                    @if($document)
                        <button onclick="openDocumentModal()" class="mt-6 flex items-center gap-3 text-[10px] tizzilla-font-heavy text-cyan-400 hover:text-white transition-all uppercase tracking-widest">
                            <i class="fas fa-eye"></i> Analizar Evidencia
                        </button>
                    @endif
                </div>

                <div class="glass-card rounded-[3rem] p-10">
                    <p class="text-[10px] tizzilla-font-heavy text-zinc-500 uppercase tracking-[0.3em] mb-4">Diferencia Neta</p>
                    @php $diff = $result['summary']['approved'] - $result['summary']['programmed']; @endphp
                    <div @class([
                        'text-7xl tizzilla-font-heavy tracking-tighter',
                        'text-emerald-500' => $diff >= 0,
                        'text-rose-500' => $diff < 0,
                    ])>
                        {{ ($diff > 0 ? '+' : '') . number_format($diff) }}
                    </div>
                </div>
            </div>

            {{-- 4. COSTOS Y BIOMETRÍA --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16">
                <div class="glass-card rounded-[3rem] p-10">
                    <h3 class="text-[11px] tizzilla-font-heavy uppercase text-white tracking-[0.4em] mb-10 flex items-center gap-4">
                        <i class="fas fa-file-invoice-dollar text-yellow-500"></i> Desglose Unitario
                    </h3>
                    <div class="space-y-4">
                        @foreach([
                            ['Costo Ave', $extras['pricing']['unit_cost'] ?? 0, 'text-white'],
                            ['Fondo FONAV', $extras['pricing']['fonav_cost'] ?? 0, 'text-cyan-400'],
                            ['Protocolo Vacunas', $extras['pricing']['vaccine_cost'] ?? 0, 'text-purple-400']
                        ] as $price)
                            <div class="flex justify-between items-center p-6 bg-black/30 rounded-2xl border border-white/5">
                                <span class="text-[10px] tizzilla-font-heavy text-zinc-500 uppercase tracking-widest">{{ $price[0] }}</span>
                                <span class="text-xl font-bold {{ $price[2] }}">$ {{ number_format($price[1], 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-card rounded-[3rem] p-10">
                    <h3 class="text-[11px] tizzilla-font-heavy uppercase text-white tracking-[0.4em] mb-10 flex items-center gap-4">
                        <i class="fas fa-shield-virus text-emerald-500"></i> Certificación Sanitaria
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach(['marek', 'gumboro'] as $vax)
                            <div class="p-6 bg-black/30 rounded-2xl border border-white/5 text-center">
                                <p class="text-[9px] tizzilla-font-heavy text-zinc-500 uppercase mb-4">{{ $vax }}</p>
                                @if($extras['vaccines'][$vax] ?? false)
                                    <span class="text-[10px] tizzilla-font-heavy text-emerald-400 bg-emerald-500/10 px-4 py-2 rounded-lg border border-emerald-500/20">CERTIFICADO</span>
                                @else
                                    <span class="text-[10px] tizzilla-font-heavy text-rose-500 bg-rose-500/10 px-4 py-2 rounded-lg border border-rose-500/20">PENDIENTE</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 5. VERIFICACIÓN DE LOTE (SOLO EL DE ESTA ORDEN) --}}
            @if($currentBatch)
            <section class="mb-16">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-10 w-1 {{ $isLocked ? 'bg-zinc-700' : 'bg-yellow-400' }}"></div>
                    <h3 class="text-2xl tizzilla-font-heavy uppercase tracking-tighter">
                        {{ $isLocked ? 'REGISTRO AUDITADO' : 'AJUSTE MAESTRO DE LOTE' }}
                    </h3>
                </div>

                <div @class([
                    'glass-card rounded-[2.5rem] p-8 transition-all',
                    'hover:border-yellow-500/30' => !$isLocked,
                    'opacity-75' => $isLocked
                ])>
                    <form method="POST" action="{{ route('poultry.batch.update', $currentBatch->id) }}">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 items-center">
                            <div>
                                <p class="text-[9px] tizzilla-font-heavy text-zinc-500 uppercase tracking-widest mb-1">Lote Fecha</p>
                                <p class="text-lg font-bold text-yellow-400">{{ $currentBatch->delivery_date->format('d/m/Y') }}</p>
                                @if($currentBatch->was_manually_edited)
                                    <span class="text-[8px] tizzilla-font-heavy text-rose-400 uppercase tracking-tighter">AJUSTADO MANUALMENTE</span>
                                @endif
                            </div>
                            
                            <div>
                                <p class="text-[9px] tizzilla-font-heavy text-cyan-500 uppercase tracking-widest mb-2">IA Det:</p>
                                <div class="text-3xl tizzilla-font-heavy text-white">{{ number_format($currentBatch->quantity) }}</div>
                            </div>

                            <div class="space-y-3">
                                <p class="text-[9px] tizzilla-font-heavy text-yellow-500 uppercase tracking-widest">Verificación Humana</p>
                                <input type="number" name="verified_quantity" 
                                    value="{{ $currentBatch->verified_quantity ?? $currentBatch->quantity }}" 
                                    {{ $isLocked ? 'disabled' : '' }}
                                    class="input-tizzilla w-full rounded-xl p-4 text-xl font-bold">
                            </div>

                            <div class="space-y-3">
                                <p class="text-[9px] tizzilla-font-heavy text-zinc-500 uppercase tracking-widest">Motivo de Ajuste</p>
                                <div class="flex gap-2">
                                    <input type="text" name="reason" required 
                                        value="{{ $currentBatch->edit_reason ?? '' }}"
                                        placeholder="{{ $isLocked ? 'Sin observaciones' : 'Ejem: Error lectura guía' }}"
                                        {{ $isLocked ? 'disabled' : '' }}
                                        class="input-tizzilla flex-1 rounded-xl p-4 text-xs font-bold">
                                    
                                    @if(!$isLocked)
                                        <button type="submit" class="bg-yellow-500 text-black px-6 rounded-xl tizzilla-font-heavy hover:scale-105 transition-all">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            @endif
             {{-- 5. VERIFICACIÓN DE LOTES (SOLO LOTES DE ESTA ORDEN) --}}
            @if(isset($batches) && $batches->count())
            <section class="mb-16">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-10 w-1 {{ $isLocked ? 'bg-zinc-700' : 'bg-yellow-400' }}"></div>
                    <h3 class="text-2xl tizzilla-font-heavy uppercase tracking-tighter">
                        {{ $isLocked ? 'HISTORIAL DE LOTES (AUDITADO)' : 'AJUSTE MAESTRO DE LOTES' }}
                    </h3>
                </div>

                <div class="space-y-6">
                    @foreach($batches as $batch)
                    <div @class([ 'glass-card rounded-[2.5rem] p-8 transition-all' , 'hover:border-yellow-500/30'=>
                        !$isLocked,
                        'opacity-75' => $isLocked
                        ])>
                        <form method="POST" action="{{ route('poultry.batch.update', $batch->id) }}">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 items-center">

                                {{-- FECHA --}}
                                <div>
                                    <p
                                        class="text-[9px] tizzilla-font-heavy text-zinc-500 uppercase tracking-widest mb-1">
                                        Lote Fecha
                                    </p>
                                    <p class="text-lg font-bold">
                                        {{ $batch->delivery_date->format('d/m/Y') }}
                                    </p>

                                    @if($batch->was_manually_edited)
                                    <span
                                        class="text-[8px] tizzilla-font-heavy text-rose-400 uppercase tracking-tighter">
                                        AJUSTADO MANUALMENTE
                                    </span>
                                    @endif
                                </div>

                                {{-- IA --}}
                                <div>
                                    <p
                                        class="text-[9px] tizzilla-font-heavy text-cyan-500 uppercase tracking-widest mb-2">
                                        IA Det:
                                    </p>
                                    <div class="text-3xl tizzilla-font-heavy text-white">
                                        {{ number_format($batch->quantity) }}
                                    </div>
                                </div>

                                {{-- VERIFICACIÓN --}}
                                <div class="space-y-3">
                                    <p class="text-[9px] tizzilla-font-heavy text-yellow-500 uppercase tracking-widest">
                                        Verificación Humana
                                    </p>

                                    <input type="number" name="verified_quantity"
                                        value="{{ $batch->verified_quantity ?? $batch->quantity }}" {{ $isLocked
                                        ? 'disabled' : '' }}
                                        class="input-tizzilla w-full rounded-xl p-4 text-xl font-bold">
                                </div>

                                {{-- MOTIVO --}}
                                <div class="space-y-3">
                                    <p class="text-[9px] tizzilla-font-heavy text-zinc-500 uppercase tracking-widest">
                                        Motivo de Ajuste
                                    </p>

                                    <div class="flex gap-2">
                                        <input type="text" name="reason" required
                                            value="{{ $batch->edit_reason ?? '' }}"
                                            placeholder="{{ $isLocked ? 'Sin observaciones' : 'Ejem: Error lectura guía' }}"
                                            {{ $isLocked ? 'disabled' : '' }}
                                            class="input-tizzilla flex-1 rounded-xl p-4 text-xs font-bold">

                                        @if(!$isLocked)
                                        <button type="submit"
                                            class="bg-yellow-500 text-black px-6 rounded-xl tizzilla-font-heavy hover:scale-105 transition-all">
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
            </section>
            @endif

            {{-- 6. ACCIONES CRÍTICAS --}}
            <div class="mt-24">
                @if(!$isLocked)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <form method="POST" action="{{ route('poultry.orders.confirm', $order) }}" class="md:col-span-3">
                            @csrf
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 py-10 rounded-[3rem] tizzilla-font-heavy uppercase tracking-[0.4em] text-white shadow-[0_20px_50px_rgba(16,185,129,0.2)] transition-all flex items-center justify-center gap-6">
                                <i class="fas fa-shield-check text-2xl"></i>
                                Validar Recepción y Cerrar Expediente
                            </button>
                        </form>

                        <form method="POST" action="{{ route('poultry.orders.incident', $order) }}" class="md:col-span-1">
                            @csrf
                            <button type="submit" class="w-full h-full bg-zinc-900 border border-rose-500/30 text-rose-500 py-10 rounded-[3rem] tizzilla-font-heavy uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                                Reportar Incidente
                            </button>
                        </form>
                    </div>
                @else
                    <div class="glass-card p-16 rounded-[4rem] text-center border-emerald-500/20 relative overflow-hidden bg-emerald-500/[0.02]">
                        <i class="fas fa-lock absolute -right-10 -bottom-10 text-white/[0.02] text-[15rem]"></i>
                        <div class="inline-flex h-20 w-20 bg-emerald-500/10 text-emerald-500 rounded-full items-center justify-center text-3xl mb-6 border border-emerald-500/20">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h3 class="text-4xl tizzilla-font-heavy uppercase tracking-tighter leading-none">Expediente Auditado</h3>
                        <p class="text-zinc-500 tizzilla-font-heavy uppercase tracking-[0.4em] text-[10px] mt-4">
                            Certificado por: <span class="text-emerald-500">{{ $order->approvedBy?->name ?? 'Sistema Central' }}</span>
                        </p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- MODAL DE EVIDENCIA --}}
    @if($document)
    <div id="documentModal" class="fixed inset-0 z-[100] hidden bg-black/95 backdrop-blur-xl items-center justify-center p-6">
        <div class="relative w-full max-w-5xl bg-zinc-900 border border-white/10 rounded-[3rem] overflow-hidden">
            <div class="p-8 border-b border-white/5 flex justify-between items-center bg-black/40">
                <h3 class="tizzilla-font-heavy text-[10px] uppercase tracking-[0.4em]">Asset_ID: {{ $document->id }}</h3>
                <button onclick="closeDocumentModal()" class="h-12 w-12 bg-white/5 rounded-2xl hover:bg-rose-500 transition-all"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-4 bg-black flex justify-center overflow-auto max-h-[70vh]">
                {{-- Reparación de ruta para evitar 403 --}}
                <img src="{{ asset('storage/' . str_replace('public/', '', $document->file_path)) }}" 
                     class="rounded-2xl max-w-full h-auto shadow-2xl">
            </div>
            <div class="p-8 bg-zinc-900 flex justify-end">
                <a href="{{ asset('storage/' . str_replace('public/', '', $document->file_path)) }}" 
                   target="_blank" class="px-8 py-4 bg-white/5 rounded-2xl tizzilla-font-heavy text-[10px] uppercase tracking-widest hover:text-yellow-400 transition-all">Abrir Original</a>
            </div>
        </div>
    </div>
    @endif

    <script>
        function openDocumentModal() { document.getElementById('documentModal').classList.replace('hidden', 'flex'); }
        function closeDocumentModal() { document.getElementById('documentModal').classList.replace('flex', 'hidden'); }
    </script>
</x-app-layout>