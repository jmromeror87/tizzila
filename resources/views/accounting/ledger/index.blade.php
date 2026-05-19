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
                {{-- Icono de Contabilidad con Glow Tizzila --}}
                <div class="relative hidden sm:block">
                    <div class="absolute -inset-1 bg-indigo-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-indigo-400 border border-indigo-500/50 shadow-[0_10px_20px_rgba(99,102,241,0.2)]">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Libro <span class="text-[#f3c444]">Mayor</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Análisis Contable Detallado
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.print()" 
                   class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                    <i class="fas fa-print text-[#f3c444]"></i> Imprimir Reporte
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-8 space-y-8">

        {{-- 📊 KPI CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Total Débitos --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Débitos</p>
                <h2 class="text-3xl font-black text-emerald-500 tracking-tighter">
                    ${{ number_format($lines->sum('debit'), 0, ',', '.') }}
                </h2>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500/40 w-full"></div>
                </div>
            </div>

            {{-- Total Créditos --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-rose-500/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Total Créditos</p>
                <h2 class="text-3xl font-black text-rose-500 tracking-tighter">
                    ${{ number_format($lines->sum('credit'), 0, ',', '.') }}
                </h2>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500/40 w-full"></div>
                </div>
            </div>

            {{-- Saldo Final --}}
            <div class="group relative bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#f3c444]/5 blur-[40px] rounded-full"></div>
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Saldo Final</p>
                <h2 class="text-3xl font-black text-white tracking-tighter">
                    ${{ number_format($lines->last()->running_balance ?? 0, 0, ',', '.') }}
                </h2>
                <div class="mt-4 h-1 w-full bg-[#f3c444]/20 rounded-full"></div>
            </div>
        </div>

        {{-- 🔍 FILTROS AVANZADOS --}}
        <div class="group relative bg-[#0a0a0c] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl">
            <form method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-3">
                    <label class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2 block">Cuenta Contable</label>
                    <select name="account_id" class="w-full bg-black border border-white/10 rounded-xl text-white text-xs font-bold px-4 py-3 focus:border-[#f3c444] transition-all uppercase">
                        <option value="">— SELECCIONE UNA CUENTA —</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @selected($selectedAccount == $account->id)>
                                {{ $account->code }} · {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="h-[46px] px-8 bg-[#f3c444] text-black font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-105 transition-all active:scale-95">
                    <i class="fas fa-search mr-2"></i> Consultar
                </button>
            </form>
        </div>

        {{-- 📋 TABLA DE MOVIMIENTOS --}}
        <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/40 border-b border-white/5">
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Fecha</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Referencia</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Descripción</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Débito</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Crédito</th>
                            <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right bg-white/[0.02]">Balance</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($lines as $line)
                            <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span class="text-[11px] font-black text-zinc-400 uppercase">
                                        {{ \Carbon\Carbon::parse($line->journalEntry->date)->format('d M, Y') }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black text-white uppercase group-hover:text-[#f3c444] transition-colors">
                                        {{ $line->journalEntry->reference }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-[11px] font-bold text-zinc-500 uppercase italic">
                                        {{ $line->description ?? 'SIN DETALLE' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right font-mono font-black text-sm {{ $line->debit > 0 ? 'text-white' : 'text-zinc-800' }}">
                                    {{ $line->debit > 0 ? number_format($line->debit, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-8 py-6 text-right font-mono font-black text-sm {{ $line->credit > 0 ? 'text-rose-500' : 'text-zinc-800' }}">
                                    {{ $line->credit > 0 ? number_format($line->credit, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-8 py-6 text-right font-mono font-black text-sm bg-white/[0.01] text-[#f3c444]">
                                    {{ number_format($line->running_balance, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <i class="fas fa-folder-open text-4xl text-zinc-800 mb-4 block"></i>
                                    <span class="text-zinc-600 font-black uppercase tracking-widest text-xs">No se encontraron movimientos contables</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>