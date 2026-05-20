{{-- TIZZILA APP - © 2026 --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-balance-scale text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tighter uppercase leading-none">
                        Balance de <span class="text-yellow-500">Comprobación</span>
                    </h2>
                    <p class="text-[9px] text-zinc-500 uppercase tracking-[0.3em] font-bold mt-1">Verificación de Partida Doble</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:block text-right">
                    <p class="text-[8px] font-black text-zinc-500 uppercase tracking-widest">Periodo Actual</p>
                    <p class="text-xs font-black text-white uppercase">
                        {{ \Carbon\Carbon::parse($from)->format('d M') }} — {{ \Carbon\Carbon::parse($to)->format('d M, Y') }}
                    </p>
                </div>
                <a href="{{ route('trial.balance.pdf', request()->all()) }}"
                    class="bg-white/5 hover:bg-red-500/10 border border-white/10 hover:border-red-500/30 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                    <i class="fas fa-file-pdf text-red-400"></i> Exportar PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-5">

        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Estado Contable</p>
                @if(round($totalDebit,2) == round($totalCredit,2))
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-base font-black text-emerald-400 uppercase">Balance Cuadrado</p>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-red-500 animate-bounce"></span>
                        <p class="text-base font-black text-red-400 uppercase">Descuadre Detectado</p>
                    </div>
                @endif
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Sumas Débitos</p>
                <p class="text-2xl font-black text-white">${{ number_format($totalDebit, 0, ',', '.') }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
                <p class="text-[9px] font-black text-zinc-500 uppercase tracking-widest mb-2">Sumas Créditos</p>
                <p class="text-2xl font-black text-white">${{ number_format($totalCredit, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Desde</label>
                    <input type="date" name="from_date" value="{{ $from }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-zinc-500 mb-1.5">Hasta</label>
                    <input type="date" name="to_date" value="{{ $to }}"
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-yellow-500/50 focus:ring-0 transition-all">
                </div>
                <button class="h-[46px] bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt"></i> Refrescar Balance
                </button>
            </form>
        </div>

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em]">Cuenta / Código PGC</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Débitos</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Créditos</th>
                            <th class="px-6 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Saldo Neto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($lines as $line)
                            <tr class="group hover:bg-white/[0.02] transition-all">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black text-white uppercase group-hover:text-yellow-500 transition-colors leading-none">{{ $line->account->name }}</p>
                                    <p class="text-[9px] font-bold text-zinc-600 mt-0.5 uppercase tracking-widest">{{ $line->account->code }}</p>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-sm text-zinc-400">
                                    {{ number_format($line->total_debit, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-sm text-zinc-400">
                                    {{ number_format($line->total_credit, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-sm {{ $line->balance > 0 ? 'text-emerald-400' : ($line->balance < 0 ? 'text-red-400' : 'text-zinc-700') }}">
                                    ${{ number_format(abs($line->balance), 0, ',', '.') }}
                                    <span class="text-[8px] opacity-50">{{ $line->balance >= 0 ? 'DB' : 'CR' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-24 text-center">
                                    <i class="fas fa-balance-scale text-5xl text-zinc-800 block mb-3"></i>
                                    <p class="text-zinc-600 font-black uppercase tracking-[0.3em] text-xs">No hay datos para este rango de fechas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t-2 border-white/5 bg-black/30">
                        <tr>
                            <td class="px-6 py-5 text-[9px] font-black text-zinc-500 uppercase tracking-widest text-right">Sumas Iguales</td>
                            <td class="px-6 py-5 text-right font-mono font-black text-lg text-white">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                            <td class="px-6 py-5 text-right font-mono font-black text-lg text-white">{{ number_format($totalCredit, 0, ',', '.') }}</td>
                            <td class="px-6 py-5 text-right">
                                <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mb-0.5">Diferencia</p>
                                <p class="text-base font-black {{ round($totalDebit - $totalCredit, 2) == 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                    ${{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
                                </p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
