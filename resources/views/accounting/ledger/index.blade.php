{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-book-open text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Libro <span class="text-yellow-500">Mayor</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">Análisis Contable Detallado</p>
                </div>
            </div>
            <button onclick="window.print()"
                class="bg-white/5 hover:bg-white/10 border border-white/10 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                <i class="fas fa-print text-yellow-500"></i> Imprimir Reporte
            </button>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Débitos</p>
                <p class="text-2xl font-black text-emerald-400">${{ number_format($lines->sum('debit'), 0, ',', '.') }}</p>
                <div class="mt-3 h-0.5 w-full bg-emerald-500/20 rounded-full"></div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Créditos</p>
                <p class="text-2xl font-black text-red-400">${{ number_format($lines->sum('credit'), 0, ',', '.') }}</p>
                <div class="mt-3 h-0.5 w-full bg-red-500/20 rounded-full"></div>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Saldo Final</p>
                <p class="text-2xl font-black text-white">${{ number_format($lines->last()->running_balance ?? 0, 0, ',', '.') }}</p>
                <div class="mt-3 h-0.5 w-full bg-yellow-500/20 rounded-full"></div>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-3">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Cuenta Contable</label>
                    <select name="account_id" class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                        <option value="">— Seleccione una cuenta —</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @selected($selectedAccount == $account->id)>
                                {{ $account->code }} · {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="h-[46px] bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Consultar
                </button>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Fecha</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Referencia</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Descripción</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Débito</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Crédito</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($lines as $line)
                            <tr class="group hover:bg-white/[0.02] transition-all">
                                <td class="px-6 py-4 text-[11px] font-black text-zinc-400 uppercase whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($line->journalEntry->date)->translatedFormat('d M, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-white uppercase group-hover:text-yellow-500 transition-colors">
                                        {{ $line->journalEntry->reference }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[11px] text-zinc-500 uppercase italic">
                                    {{ $line->description ?? 'Sin detalle' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-sm {{ $line->debit > 0 ? 'text-emerald-400' : 'text-zinc-800' }}">
                                    {{ $line->debit > 0 ? number_format($line->debit, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-sm {{ $line->credit > 0 ? 'text-red-400' : 'text-zinc-800' }}">
                                    {{ $line->credit > 0 ? number_format($line->credit, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-sm text-yellow-500">
                                    {{ number_format($line->running_balance, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <i class="fas fa-book-open text-5xl text-zinc-800 block mb-3"></i>
                                    <p class="text-zinc-600 font-black uppercase tracking-[0.3em] text-xs">No se encontraron movimientos contables</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
