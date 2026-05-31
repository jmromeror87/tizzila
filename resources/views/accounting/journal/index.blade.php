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
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-layer-group text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Libro <span class="text-yellow-500">Diario</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">
                        Registro Cronológico de Operaciones
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-3 px-4 py-2 bg-white/5 border border-white/10 rounded-xl">
                    <i class="fas fa-chart-line text-emerald-400 text-xs"></i>
                    <div>
                        <p class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Movimiento Hoy</p>
                        <p class="text-xs font-black text-emerald-400 font-mono">${{ number_format($todayTotal ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <a href="{{ route('journal.create') }}"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-[0_8px_20px_rgba(234,179,8,0.2)]">
                    <i class="fas fa-plus"></i> Nuevo Asiento
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- AUDITORÍA DE PERÍODOS --}}
        @php
            $now = now();
            $currentYear = $now->year;
            $currentMonth = $now->month;
        @endphp

        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-black text-yellow-500 bg-yellow-500/10 border border-yellow-500/20 px-2 py-0.5 rounded-lg">PERÍODOS</span>
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">Auditoría de Períodos</h3>
                </div>
                <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-black/40 border border-white/5">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] font-black text-zinc-300 uppercase tracking-widest">{{ $now->translatedFormat('F Y') }}</span>
                </div>
            </div>
            <div class="px-6 py-4 flex flex-wrap gap-2">
                @foreach($periods as $p)
                    @php
                        $isCurrent = ($p->year == $currentYear && $p->month == $currentMonth);
                        $isClosed  = ($p->status === 'closed');
                        $carbonDate = \Carbon\Carbon::create($p->year, $p->month);
                    @endphp
                    <div class="group relative px-3 py-2 rounded-xl border transition-all
                        {{ $isClosed ? 'bg-red-500/5 border-red-500/20 text-red-400/70' : 'bg-emerald-500/5 border-emerald-500/20 text-emerald-400' }}
                        {{ $isCurrent ? 'ring-1 ring-yellow-500/30' : '' }}">
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-black uppercase tracking-widest font-mono">
                                {{ $carbonDate->translatedFormat('M Y') }}
                            </span>
                            @if($isClosed)
                                <i class="fas fa-lock text-[8px] opacity-60"></i>
                            @else
                                <i class="fas fa-circle-dot text-[7px] {{ $isCurrent ? 'animate-pulse' : '' }}"></i>
                            @endif
                        </div>
                        @if($isClosed)
                        <div class="absolute inset-0 bg-black/80 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <form method="POST" action="{{ route('accounting.periods.reopen') }}">
                                @csrf
                                <input type="hidden" name="year" value="{{ $p->year }}">
                                <input type="hidden" name="month" value="{{ $p->month }}">
                                <button type="submit" class="text-[8px] font-black text-yellow-400 uppercase tracking-widest hover:text-white transition-colors">
                                    <i class="fas fa-unlock-alt mr-1"></i> Reabrir
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Rango Inicial</label>
                    <div class="relative">
                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-9 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Rango Final</label>
                    <div class="relative">
                        <i class="fas fa-calendar-check absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-9 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Global Search</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-600 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por ref o concepto..."
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold pl-9 pr-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all placeholder:text-zinc-700">
                    </div>
                </div>
                <button class="h-[46px] bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-yellow-500 hover:text-black transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-bolt"></i> Ejecutar Filtro
                </button>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Metadata / Cronología</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Comprobante / Operación</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Tercero</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Débito</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Crédito</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-center">Gestión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($entries as $entry)

                            {{-- BANNER REVERSADO --}}
                            @if($entry->reversed_at)
                            <tr class="bg-red-500/[0.03]">
                                <td colspan="6" class="px-6 py-2 border-l-2 border-red-500">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-ban text-red-400 text-[10px]"></i>
                                        <span class="text-[9px] font-black text-red-400 uppercase tracking-widest">Operación Reversada</span>
                                        <span class="text-[9px] text-zinc-600">"{{ $entry->reversal_reason }}"</span>
                                        <span class="text-[8px] text-zinc-700 ml-auto">{{ \Carbon\Carbon::parse($entry->reversed_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endif

                            {{-- CABECERA ASIENTO --}}
                            <tr class="group border-l-2 {{ $entry->reversed_at ? 'opacity-40 border-red-900' : 'border-yellow-500/30 hover:border-yellow-500' }} transition-all">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="text-center bg-black/40 px-2 py-1.5 rounded-xl border border-white/5 min-w-[44px]">
                                            <p class="text-[7px] font-black text-zinc-600 uppercase">{{ \Carbon\Carbon::parse($entry->date)->translatedFormat('M') }}</p>
                                            <p class="text-base font-black text-white leading-none">{{ \Carbon\Carbon::parse($entry->date)->format('d') }}</p>
                                            <p class="text-[7px] font-black text-zinc-600">{{ \Carbon\Carbon::parse($entry->date)->format('Y') }}</p>
                                        </div>
                                        <a href="{{ route('journal.show', $entry->id) }}"
                                            class="text-[9px] font-bold text-yellow-500 hover:underline uppercase">REF: {{ $entry->reference }}</a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[11px] font-black text-white uppercase tracking-tight group-hover:text-yellow-500 transition-colors">{{ $entry->description }}</p>
                                    <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mt-0.5">Usuario: {{ $entry->user->name ?? 'System' }}</p>
                                </td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-zinc-300 font-mono">{{ number_format($entry->total_debit, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-zinc-300 font-mono">{{ number_format($entry->total_credit, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$entry->reversed_at)
                                    <button onclick="openModal({{ $entry->id }}, '{{ $entry->reference }}')"
                                        class="h-8 w-8 bg-white/5 border border-white/10 hover:bg-red-500/20 hover:border-red-500/30 rounded-xl transition-all flex items-center justify-center mx-auto">
                                        <i class="fas fa-arrow-rotate-left text-[10px] text-zinc-500 hover:text-red-400"></i>
                                    </button>
                                    @else
                                    <i class="fas fa-shield-halved text-zinc-800 text-xs"></i>
                                    @endif
                                </td>
                            </tr>

                            {{-- LÍNEAS --}}
                            @foreach($entry->lines as $line)
                            <tr class="{{ $entry->reversed_at ? 'opacity-30' : 'hover:bg-white/[0.01]' }} transition-all">
                                <td></td>
                                <td class="px-6 py-2 pl-14 border-l border-white/5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[8px] font-black text-blue-400 bg-blue-500/10 border border-blue-500/20 px-1.5 py-0.5 rounded font-mono">{{ $line->account->code }}</span>
                                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-tight">{{ $line->account->name }}</span>
                                        @if($line->description)
                                            <span class="text-[8px] text-zinc-700 uppercase">// {{ $line->description }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-2">
                                    @if($line->third_party_id)
                                        @php
                                            $thirdParty = null;
                                            if ($line->third_party_type === 'customer') {
                                                $thirdParty = \App\Models\Customer\Customer::find($line->third_party_id);
                                            } elseif ($line->third_party_type === 'provider') {
                                                $thirdParty = \App\Models\Poultry\Provider::find($line->third_party_id);
                                            }
                                        @endphp
                                        @if($thirdParty)
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[7px] font-black uppercase px-1.5 py-0.5 rounded border
                                                {{ $line->third_party_type === 'customer' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20' }}">
                                                {{ $line->third_party_type === 'customer' ? 'CLI' : 'PRV' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-zinc-300 uppercase">{{ $thirdParty->business_name ?? $thirdParty->name ?? '—' }}</span>
                                        </div>
                                        @endif
                                    @else
                                        <span class="text-[9px] text-zinc-800">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-2 text-right font-mono text-[11px] font-black {{ $line->debit > 0 ? 'text-emerald-400' : 'text-zinc-800' }}">
                                    {{ $line->debit > 0 ? number_format($line->debit, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-6 py-2 text-right font-mono text-[11px] font-black {{ $line->credit > 0 ? 'text-red-400' : 'text-zinc-800' }}">
                                    {{ $line->credit > 0 ? number_format($line->credit, 0, ',', '.') : '—' }}
                                </td>
                                <td></td>
                            </tr>
                            @endforeach

                            <tr class="h-3 bg-black/20"><td colspan="6"></td></tr>

                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <i class="fas fa-layer-group text-5xl text-zinc-800 block mb-3"></i>
                                <p class="text-zinc-600 font-black uppercase tracking-[0.3em] text-xs">Sin registros contables</p>
                                <p class="text-[10px] text-zinc-700 uppercase mt-1">Ajuste los filtros o cree un nuevo asiento</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINACIÓN --}}
        @if($entries->hasPages())
        <div class="flex justify-center">
            {{ $entries->links() }}
        </div>
        @endif

    </div>

    {{-- MODAL REVERSIÓN --}}
    <div id="reverseModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal()"></div>
        <div id="modalContainer"
            class="relative bg-[#0d121f] border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-90 opacity-0">
            <div class="p-8 space-y-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400">
                        <i class="fas fa-triangle-exclamation text-xl animate-pulse"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-white uppercase tracking-tight">Reversar Operación</h2>
                        <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">Protocolo de auditoría</p>
                    </div>
                </div>

                <form method="POST" id="reverseForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-black/30 border border-white/5 rounded-xl">
                            <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mb-1">Documento REF</p>
                            <span id="modal-ref-label" class="text-xs font-black text-yellow-500 uppercase">---</span>
                        </div>
                        <div class="p-3 bg-black/30 border border-white/5 rounded-xl">
                            <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mb-1">Fecha</p>
                            <span class="text-xs font-black text-white uppercase">{{ now()->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Justificación del Auditor</label>
                        <textarea name="reason" id="reverseReason" required rows="4"
                            class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs p-4 focus:border-red-500/50 focus:ring-0 placeholder:text-zinc-700 resize-none transition-all"
                            placeholder="Describa el motivo de la reversión..."></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeModal()"
                            class="flex-1 py-3 text-zinc-500 text-[10px] font-black uppercase tracking-widest hover:text-white transition-all border border-white/5 rounded-xl">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-[2] py-3 bg-red-500 hover:bg-red-400 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-95">
                            <i class="fas fa-shield-halved mr-2"></i> Ejecutar Reversión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id, reference) {
            const modal = document.getElementById('reverseModal');
            const container = document.getElementById('modalContainer');
            document.getElementById('reverseForm').action = "{{ route('journal.reverse', ':id') }}".replace(':id', id);
            document.getElementById('modal-ref-label').innerText = reference;
            modal.classList.replace('hidden', 'flex');
            setTimeout(() => {
                container.classList.remove('scale-90', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        }
        function closeModal() {
            const container = document.getElementById('modalContainer');
            container.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                document.getElementById('reverseModal').classList.replace('flex', 'hidden');
                document.getElementById('reverseReason').value = '';
            }, 300);
        }
    </script>
</x-app-layout>
