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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4 py-2">
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-blue-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-blue-400 border border-blue-500/50 shadow-[0_10px_20px_rgba(59,130,246,0.2)]">
                        <i class="fas fa-layer-group text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Libro <span class="text-[#f3c444]">Diario</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Registro Cronológico de Operaciones
                    </p>
                </div>
            </div>

            {{-- ✅ FIX 1: mensaje success movido fuera del header para no romper el layout --}}
            <div class="flex items-center gap-3">
                <div class="hidden lg:flex items-center gap-4 px-6 py-2 bg-black/40 border border-white/5 rounded-2xl mr-4">
                    <div class="text-right">
                        <p class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Movimiento Hoy</p>
                        <p class="text-xs font-mono font-black text-emerald-400">${{ number_format($todayTotal ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="h-8 w-[1px] bg-white/10"></div>
                    <div class="h-8 w-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                        <i class="fas fa-chart-line text-xs"></i>
                    </div>
                </div>

                <a href="{{ route('journal.create') }}"
                    class="group px-6 py-3 bg-[#f3c444] text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 active:scale-95 transition-all shadow-lg shadow-[#f3c444]/20 flex items-center gap-2">
                    <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i> Nuevo Asiento
                </a>
            </div>
        </div>
    </x-slot>

    {{-- ✅ FIX 1: success aquí, visible y bien posicionado --}}
    @if(session('success'))
    <div class="mx-8 mt-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl text-xs font-bold flex items-center gap-3">
        <i class="fas fa-circle-check text-emerald-500"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- 📊 PANEL DE PERIODOS CONTABLES --}}
    @php
        $now = now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
    @endphp

    <div class="px-8 mt-8">
        <div class="bg-[#0a0a0c]/40 p-6 rounded-[2.5rem] border border-white/5 shadow-inner backdrop-blur-md relative overflow-hidden">
            <div class="absolute -right-10 -top-10 h-40 w-40 bg-blue-500/5 blur-[80px] rounded-full"></div>

            <div class="flex items-center justify-between mb-6 px-1 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-zinc-800/50 rounded-lg text-zinc-500">
                        <i class="fas fa-timeline text-[10px]"></i>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.3em]">Auditoría de Períodos</h3>
                        <p class="text-[8px] text-zinc-600 uppercase font-bold tracking-widest mt-0.5">Control de acceso mensual</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/60 border border-white/5 shadow-xl">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] font-black text-zinc-300 uppercase tracking-widest">{{ $now->translatedFormat('F Y') }}</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 relative z-10">
                @foreach($periods as $p)
                    @php
                        $isCurrent = ($p->year == $currentYear && $p->month == $currentMonth);
                        $isClosed  = ($p->status === 'closed');
                        $carbonDate = \Carbon\Carbon::create($p->year, $p->month);
                    @endphp

                    <div class="group relative px-4 py-3 rounded-2xl transition-all duration-300 border
                        {{ $isClosed ? 'bg-rose-500/5 text-rose-400/70 border-rose-500/20' : 'bg-emerald-500/5 text-emerald-400 border-emerald-500/20' }}
                        {{ $isCurrent ? 'ring-2 ring-blue-500/40 shadow-lg shadow-blue-500/5' : '' }}">

                        <div class="flex items-center gap-3">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase tracking-widest font-mono">
                                    {{ $carbonDate->translatedFormat('M Y') }}
                                </span>
                                @if($isCurrent)
                                    <span class="text-[7px] font-black text-blue-400 uppercase tracking-tighter">Actual</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                @if($isClosed)
                                    <i class="fas fa-lock text-[9px] opacity-60"></i>
                                @else
                                    <i class="fas fa-circle-dot {{ $isCurrent ? 'animate-pulse' : '' }} text-[8px]"></i>
                                @endif
                            </div>
                        </div>

                        @if($isClosed)
                        <div class="absolute inset-0 bg-black/80 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <form method="POST" action="{{ route('accounting.periods.reopen') }}">
                                @csrf
                                <input type="hidden" name="year" value="{{ $p->year }}">
                                <input type="hidden" name="month" value="{{ $p->month }}">
                                <button type="submit" class="text-[8px] font-black text-blue-400 uppercase tracking-widest hover:text-white transition-colors">
                                    <i class="fas fa-unlock-alt mr-1"></i> Reabrir
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="p-8 space-y-8">
        {{-- 🔍 FILTROS --}}
        <div class="relative bg-[#0a0a0c] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-[#f3c444]/20 to-transparent"></div>
            <form method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em] ml-2">Rango Inicial</label>
                    <div class="relative">
                        <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-zinc-700"></i>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold pl-12 pr-4 py-3.5 focus:border-[#f3c444] focus:ring-0 transition-all shadow-inner">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em] ml-2">Rango Final</label>
                    <div class="relative">
                        <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-zinc-700"></i>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                            class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold pl-12 pr-4 py-3.5 focus:border-[#f3c444] focus:ring-0 transition-all shadow-inner">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-zinc-600 uppercase tracking-[0.3em] ml-2">Global Search</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-zinc-700"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="BUSCAR POR REF O CONCEPTO..."
                            class="w-full bg-black border border-white/10 rounded-xl text-white text-[10px] font-bold pl-12 pr-4 py-3.5 focus:border-[#f3c444] focus:ring-0 transition-all uppercase placeholder:text-zinc-800 shadow-inner">
                    </div>
                </div>
                <button class="group h-[50px] px-8 bg-[#f3c444]/5 border border-[#f3c444]/20 text-[#f3c444] font-black text-[10px] uppercase tracking-[0.2em] rounded-xl hover:bg-[#f3c444] hover:text-black transition-all duration-500 shadow-lg shadow-[#f3c444]/5 flex items-center justify-center">
                    <i class="fas fa-bolt mr-3 group-hover:animate-bounce"></i> Ejecutar Filtro
                </button>
            </form>
        </div>

        {{-- 📋 TABLA DE ASIENTOS --}}
        <div class="bg-[#0a0a0c] rounded-[3rem] border border-white/5 overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/60 border-b border-white/5">
                            <th class="px-8 py-7 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em]">Metadata / Cronología</th>
                            <th class="px-8 py-7 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em]">Comprobante / Operación</th>
                            {{-- ✅ FIX 2: columna Tercero agregada --}}
                            <th class="px-8 py-7 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em]">Tercero</th>
                            <th class="px-8 py-7 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Débito</th>
                            <th class="px-8 py-7 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Crédito</th>
                            <th class="px-8 py-7 text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] text-center">Gestión</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/[0.02]">
                        @forelse($entries as $entry)

                            {{-- BANNER REVERSADO --}}
                            @if($entry->reversed_at)
                            <tr class="bg-rose-500/[0.03]">
                                <td colspan="6" class="px-8 py-3 border-l-4 border-rose-500 shadow-inner">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="h-6 w-6 rounded-md bg-rose-500/20 flex items-center justify-center text-rose-500">
                                                <i class="fas fa-ban text-[10px]"></i>
                                            </div>
                                            <span class="text-[10px] font-black text-rose-400 uppercase tracking-[0.15em]">Operación Reversada por Auditoría</span>
                                            <div class="h-4 w-[1px] bg-rose-500/20"></div>
                                            <span class="text-[9px] font-bold text-zinc-500 uppercase">"{{ $entry->reversal_reason }}"</span>
                                        </div>
                                        <span class="text-[8px] font-black text-rose-500/50 uppercase tracking-widest">{{ \Carbon\Carbon::parse($entry->reversed_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endif

                            {{-- CABECERA DEL ASIENTO --}}
                            <tr class="group {{ $entry->reversed_at ? 'opacity-40 grayscale' : 'bg-white/[0.01]' }} border-l-4 {{ $entry->reversed_at ? 'border-rose-900' : 'border-[#f3c444]/40 hover:border-[#f3c444]' }} transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="text-center bg-black/40 p-2 rounded-xl border border-white/5 w-14">
                                            <p class="text-[8px] font-black text-zinc-600 uppercase">{{ \Carbon\Carbon::parse($entry->date)->format('M') }}</p>
                                            <p class="text-lg font-black text-white leading-none">{{ \Carbon\Carbon::parse($entry->date)->format('d') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($entry->date)->format('Y') }}</p>
                                            <a href="{{ route('journal.show', $entry->id) }}"
                                                class="text-[9px] font-bold text-[#f3c444] hover:underline uppercase">REF: {{ $entry->reference }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[11px] font-black text-white uppercase tracking-tight group-hover:text-[#f3c444] transition-colors">{{ $entry->description }}</span>
                                            @if($entry->reversed_at)
                                                <i class="fas fa-lock-open text-rose-500 text-[8px] opacity-40"></i>
                                            @endif
                                        </div>
                                        <span class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mt-1">
                                            Usuario: {{ $entry->user->name ?? 'System' }}
                                        </span>
                                    </div>
                                </td>
                                {{-- Cabecera: celda tercero vacía (el tercero se muestra en las líneas) --}}
                                <td class="px-8 py-6"></td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-sm font-black text-zinc-300 font-mono">{{ number_format($entry->total_debit, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-sm font-black text-zinc-300 font-mono">{{ number_format($entry->total_credit, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if(!$entry->reversed_at)
                                    <button onclick="openModal({{ $entry->id }}, '{{ $entry->reference }}')"
                                        class="group relative inline-flex items-center justify-center w-10 h-10 bg-[#0d121f] border border-white/5 rounded-2xl transition-all hover:bg-rose-600 hover:border-rose-600 shadow-xl overflow-hidden">
                                        <i class="fas fa-arrow-rotate-left text-[10px] text-zinc-500 group-hover:text-white group-hover:rotate-[-45deg] transition-all"></i>
                                    </button>
                                    @else
                                    <div class="w-10 h-10 mx-auto flex items-center justify-center text-zinc-800">
                                        <i class="fas fa-shield-halved text-xs"></i>
                                    </div>
                                    @endif
                                </td>
                            </tr>

                            {{-- DETALLE DE LÍNEAS --}}
                            @foreach($entry->lines as $line)
                            <tr class="group hover:bg-white/[0.02] transition-all {{ $entry->reversed_at ? 'opacity-30' : '' }}">
                                <td></td>
                                <td class="px-8 py-3 pl-16 border-l border-white/5">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[9px] font-black text-blue-500 font-mono bg-blue-500/10 px-2 py-0.5 rounded">{{ $line->account->code }}</span>
                                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-tight group-hover:text-white transition-colors">
                                            {{ $line->account->name }}
                                        </span>
                                        @if($line->description)
                                            <span class="text-[8px] font-bold text-zinc-700 uppercase ml-2">// {{ $line->description }}</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- ✅ FIX 2: TERCERO EN CADA LÍNEA (EL CORE DEL AJUSTE) --}}
                                <td class="px-8 py-3">
                                    @if($line->third_party_id)
                                        @php
                                            // ✅ FIX 3: cargamos el tercero según su tipo
                                            $thirdParty = null;
                                            if ($line->third_party_type === 'customer') {
                                                $thirdParty = \App\Models\Customer\Customer::find($line->third_party_id);
                                            } elseif ($line->third_party_type === 'provider') {
                                                $thirdParty = \App\Models\Poultry\Provider::find($line->third_party_id);
                                            }
                                        @endphp
                                        @if($thirdParty)
                                        <div class="flex items-center gap-2">
                                            {{-- Badge tipo --}}
                                            <span class="text-[7px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded
                                                {{ $line->third_party_type === 'customer'
                                                    ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20'
                                                    : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                                {{ $line->third_party_type === 'customer' ? 'CLI' : 'PRV' }}
                                            </span>
                                            {{-- Nombre --}}
                                            <span class="text-[10px] font-bold text-zinc-300 uppercase tracking-tight">
                                                {{ $thirdParty->business_name ?? $thirdParty->name ?? '—' }}
                                            </span>
                                        </div>
                                        @else
                                            <span class="text-[9px] text-zinc-700">—</span>
                                        @endif
                                    @else
                                        <span class="text-[9px] text-zinc-700">—</span>
                                    @endif
                                </td>

                                <td class="px-8 py-3 text-right font-mono text-[11px] font-black {{ $line->debit > 0 ? 'text-emerald-500/80' : 'text-zinc-900' }}">
                                    {{ $line->debit > 0 ? number_format($line->debit, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-8 py-3 text-right font-mono text-[11px] font-black {{ $line->credit > 0 ? 'text-rose-500/80' : 'text-zinc-900' }}">
                                    {{ $line->credit > 0 ? number_format($line->credit, 0, ',', '.') : '—' }}
                                </td>
                                <td></td>
                            </tr>
                            @endforeach

                            {{-- Espaciado entre asientos --}}
                            <tr class="h-4"></tr>

                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-32 text-center">
                                <div class="relative inline-block">
                                    <i class="fas fa-box-open text-6xl text-zinc-800 mb-4 block"></i>
                                    <i class="fas fa-search text-xl text-zinc-700 absolute bottom-4 -right-2"></i>
                                </div>
                                <span class="text-zinc-600 font-black uppercase tracking-[0.4em] text-xs block">Sin registros contables</span>
                                <p class="text-[10px] font-bold text-zinc-700 uppercase mt-2">Ajuste los filtros o inicie un nuevo asiento</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINACIÓN --}}
        @if($entries->hasPages())
        <div class="px-10 py-6 bg-[#0a0a0c] rounded-[2rem] border border-white/5 flex justify-center shadow-2xl">
            {{ $entries->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL DE ANULACIÓN --}}
    <div id="reverseModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 transition-all duration-500">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md" onclick="closeModal()"></div>
        <div id="modalContainer"
            class="relative bg-[#0a0a0c] border border-white/10 rounded-[3rem] shadow-[0_0_100px_rgba(225,29,72,0.1)] w-full max-w-lg overflow-hidden transform transition-all scale-90 opacity-0">
            <div class="absolute top-0 right-0 p-8 opacity-[0.03] text-rose-500 pointer-events-none">
                <i class="fas fa-trash-can text-9xl"></i>
            </div>

            <div class="p-10 space-y-8">
                <div class="flex items-center gap-5">
                    <div class="h-14 w-14 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500 border border-rose-500/20 shadow-lg shadow-rose-500/5">
                        <i class="fas fa-triangle-exclamation text-2xl animate-pulse"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Reversar Operación</h2>
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Protocolo de anulación de auditoría</p>
                    </div>
                </div>

                <form method="POST" id="reverseForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-5 bg-white/[0.02] border border-white/5 rounded-2xl">
                            <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mb-1">Documento REF</p>
                            <span id="modal-ref-label" class="text-xs font-black text-[#f3c444] uppercase tracking-tighter">---</span>
                        </div>
                        <div class="p-5 bg-white/[0.02] border border-white/5 rounded-2xl">
                            <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mb-1">Fecha Acción</p>
                            <span class="text-xs font-black text-white uppercase tracking-tighter">{{ now()->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest ml-2">Justificación del Auditor</label>
                        <textarea name="reason" id="reverseReason" required
                            class="w-full bg-black border border-white/10 rounded-2xl text-white text-xs p-6 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 h-40 placeholder:text-zinc-800 resize-none shadow-inner leading-relaxed transition-all"
                            placeholder="Describa el error técnico o administrativo que motiva esta anulación..."></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="closeModal()"
                            class="flex-1 py-5 text-zinc-500 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all">Abortar</button>
                        <button type="submit"
                            class="flex-[2] py-5 bg-rose-600 text-white text-[11px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-rose-500 shadow-[0_15px_40px_rgba(225,29,72,0.3)] transition-all active:scale-95">
                            Ejecutar Reversión <i class="fas fa-shield-halved ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .font-mono { font-family: 'JetBrains Mono', 'Fira Code', monospace; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #333; }
    </style>

    <script>
        function openModal(id, reference) {
            const modal = document.getElementById('reverseModal');
            const container = document.getElementById('modalContainer');
            const form = document.getElementById('reverseForm');
            const label = document.getElementById('modal-ref-label');
            let url = "{{ route('journal.reverse', ':id') }}";
            form.action = url.replace(':id', id);
            label.innerText = reference;
            modal.classList.replace('hidden', 'flex');
            setTimeout(() => {
                container.classList.remove('scale-90', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('reverseModal');
            const container = document.getElementById('modalContainer');
            container.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.classList.replace('flex', 'hidden');
                document.getElementById('reverseReason').value = '';
            }, 300);
        }
    </script>
</x-app-layout>