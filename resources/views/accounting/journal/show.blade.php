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
                    <div class="absolute -inset-1 bg-emerald-500/20 blur-xl rounded-full"></div>
                    <div class="relative bg-[#0d121f] h-12 w-12 rounded-[1rem] flex items-center justify-center text-emerald-500 border border-emerald-500/50 shadow-[0_10px_20px_rgba(16,185,129,0.2)]">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">
                        Detalle del <span class="text-[#f3c444]">Asiento</span>
                    </h1>
                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] mt-1">
                        Comprobante de Contabilidad #{{ $entry->reference }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('journal.index') }}"
                   class="px-6 py-3 bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Volver al Diario
                </a>
                <button onclick="window.print()"
                        class="px-6 py-3 bg-[#f3c444] text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:scale-105 transition-all shadow-lg shadow-[#f3c444]/20 flex items-center gap-2">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-8 max-w-5xl mx-auto space-y-8">

        {{-- 📋 CABECERA DEL COMPROBANTE --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Fecha de Emisión</p>
                <h3 class="text-xl font-black text-white uppercase leading-none">
                    {{ \Carbon\Carbon::parse($entry->date)->format('d M, Y') }}
                </h3>
            </div>

            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Referencia / Código</p>
                <h3 class="text-xl font-black text-[#f3c444] uppercase leading-none">
                    {{ $entry->reference }}
                </h3>
            </div>

            <div class="bg-[#0a0a0c] p-6 rounded-[2rem] border border-white/5 shadow-2xl">
                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Estado del Registro</p>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 uppercase tracking-tighter">
                    Contabilizado
                </span>
            </div>
        </div>

        {{-- 📝 DESCRIPCIÓN --}}
        <div class="bg-[#0a0a0c] p-8 rounded-[2.5rem] border border-white/5 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas fa-quote-right text-6xl text-white"></i>
            </div>
            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-3">Descripción General</p>
            <p class="text-lg font-bold text-zinc-300 leading-relaxed uppercase tracking-tight">
                {{ $entry->description }}
            </p>
        </div>

        {{-- 📋 TABLA DE MOVIMIENTOS --}}
        <div class="bg-[#0a0a0c] rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/40 border-b border-white/5">
                        <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Cuenta Contable / Código</th>
                        {{-- ✅ COLUMNA TERCERO AGREGADA --}}
                        <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em]">Tercero</th>
                        <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Débito</th>
                        <th class="px-8 py-6 text-[10px] font-black text-zinc-500 uppercase tracking-[0.2em] text-right">Crédito</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/[0.03]">
                    @foreach($entry->lines as $line)
                        @php
                            // ✅ Resolver tercero según tipo
                            $thirdParty = null;
                            if ($line->third_party_id) {
                                if ($line->third_party_type === 'customer') {
                                    $thirdParty = \App\Models\Customer\Customer::find($line->third_party_id);
                                } elseif ($line->third_party_type === 'provider') {
                                    $thirdParty = \App\Models\Poultry\Provider::find($line->third_party_id);
                                }
                            }
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors group">

                            {{-- Cuenta --}}
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-white uppercase group-hover:text-[#f3c444] transition-colors">
                                        {{ $line->account->name }}
                                    </span>
                                    <span class="text-[10px] font-bold text-zinc-600 mt-1 uppercase tracking-widest">
                                        {{ $line->account->code }}
                                    </span>
                                </div>
                            </td>

                            {{-- ✅ TERCERO --}}
                            <td class="px-8 py-6">
                                @if($thirdParty)
                                    <div class="flex items-center gap-2">
                                        <span class="text-[7px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded
                                            {{ $line->third_party_type === 'customer'
                                                ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20'
                                                : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                            {{ $line->third_party_type === 'customer' ? 'CLI' : 'PRV' }}
                                        </span>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-zinc-300 uppercase tracking-tight">
                                                {{ $thirdParty->business_name ?? $thirdParty->name ?? '—' }}
                                            </span>
                                            {{-- NIT / Documento si existe --}}
                                            @if(isset($thirdParty->nit) || isset($thirdParty->document_number))
                                                <span class="text-[8px] font-bold text-zinc-600 uppercase tracking-widest mt-0.5">
                                                    NIT: {{ $thirdParty->nit ?? $thirdParty->document_number }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-zinc-700 text-[9px]">—</span>
                                @endif
                            </td>

                            {{-- Débito --}}
                            <td class="px-8 py-6 text-right font-mono font-black text-sm {{ $line->debit > 0 ? 'text-white' : 'text-zinc-800' }}">
                                {{ $line->debit > 0 ? number_format($line->debit, 0, ',', '.') : '—' }}
                            </td>

                            {{-- Crédito --}}
                            <td class="px-8 py-6 text-right font-mono font-black text-sm {{ $line->credit > 0 ? 'text-rose-500' : 'text-zinc-800' }}">
                                {{ $line->credit > 0 ? number_format($line->credit, 0, ',', '.') : '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="bg-black border-t-2 border-white/5">
                    <tr>
                        {{-- ✅ colspan ajustado a 4 columnas --}}
                        <td colspan="2" class="px-8 py-8 text-right font-black text-[10px] uppercase text-zinc-500 tracking-[0.3em]">Balance Total</td>
                        <td class="px-8 py-8 text-right font-mono font-black text-xl text-white">
                            {{ number_format($entry->total_debit, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-8 text-right font-mono font-black text-xl text-rose-500">
                            {{ number_format($entry->total_credit, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- FIRMAS (SOLO VISIBLE AL IMPRIMIR) --}}
        <div class="hidden print:grid grid-cols-2 gap-20 mt-20">
            <div class="border-t border-zinc-400 pt-4 text-center">
                <p class="text-[10px] font-black uppercase text-zinc-600">Preparado por</p>
            </div>
            <div class="border-t border-zinc-400 pt-4 text-center">
                <p class="text-[10px] font-black uppercase text-zinc-600">Revisado y Aprobado</p>
            </div>
        </div>

    </div>
</x-app-layout>