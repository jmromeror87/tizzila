{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                    Auditoría de <span class="text-yellow-500">Pagos</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Control de Ingresos y Recaudos
                </p>
            </div>
            @isset($invoice)
                <a href="{{ route('cartera.index') }}"
                   class="h-9 px-5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest w-fit">
                    <i class="fas fa-arrow-left text-xs"></i> Volver
                </a>
            @endisset
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        {{-- Filtros --}}
        <form method="GET">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-5">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Buscar por Cliente o Doc</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="EJ: Juan Perez / #1024"
                           class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-gray-700 uppercase">
                </div>
                <div class="md:col-span-3">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Método</label>
                    <select name="method"
                            class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer uppercase">
                        <option value="">Todos</option>
                        <option value="cash" @selected(request('method') == 'cash')>Efectivo</option>
                        <option value="transfer" @selected(request('method') == 'transfer')>Transferencia</option>
                        <option value="card" @selected(request('method') == 'card')>Tarjeta</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1.5 block">Fecha</label>
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors">
                </div>
                <div class="md:col-span-2">
                    <button type="submit"
                            class="w-full h-9 bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest rounded-xl transition-all active:scale-95">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabla de pagos --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Referencia</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Cliente</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Ingreso Neto</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Método</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em]">Fecha y Hora</th>
                            <th class="px-5 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.3em] text-right">Gestión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($payments as $payment)
                            @php
                                $allocation = $payment->allocations->first();
                                $invoice    = optional($allocation)->invoice;
                                $customer   = optional($invoice)->customer;
                                $code       = str_pad($payment->id ?? 0, 6, '0', STR_PAD_LEFT);
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-black/40 border border-white/5 flex items-center justify-center group-hover:border-yellow-500/30 transition-colors">
                                            <i class="fas fa-file-invoice-dollar text-[9px] text-gray-600 group-hover:text-yellow-500 transition-colors"></i>
                                        </div>
                                        <span class="text-sm font-black text-white uppercase">REC-{{ $code }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs font-black text-white uppercase group-hover:text-yellow-400 transition-colors">
                                        {{ $customer->name ?? 'Consumidor Final' }}
                                    </p>
                                    <p class="text-[9px] text-gray-600 font-bold uppercase tracking-widest mt-0.5">
                                        {{ $customer->identification_number ?? '—' }}
                                    </p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-black text-emerald-500">${{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border bg-zinc-500/10 text-zinc-400 border-zinc-500/20">
                                        {{ $payment->payment_method ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-[10px] font-black text-white uppercase">{{ $payment->created_at->translatedFormat('d M, Y') }}</p>
                                    <p class="text-[9px] text-gray-600 font-bold">{{ $payment->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('cartera.receipt', $payment->id) }}"
                                           class="h-8 px-3 bg-blue-500/10 border border-blue-500/20 rounded-lg text-blue-400 text-[9px] font-black uppercase hover:bg-blue-500 hover:text-white transition-all flex items-center gap-1.5">
                                            <i class="fas fa-print text-[9px]"></i> Recibo
                                        </a>
                                        @if($payment->invoice)
                                            <a href="{{ route('invoices.show', $payment->invoice->id) }}"
                                               class="h-8 w-8 inline-flex items-center justify-center bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:text-yellow-500 hover:border-yellow-500/30 transition-all">
                                                <i class="fas fa-eye text-[10px]"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <i class="fas fa-search-dollar text-4xl text-gray-800 block mb-3"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">No se encontraron movimientos</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        @if($payments->hasPages())
            <div class="flex items-center justify-between px-1">
                <a href="{{ $payments->previousPageUrl() }}"
                   class="h-9 w-9 rounded-xl bg-[#0d121f] border border-white/5 flex items-center justify-center text-gray-500 transition-all {{ $payments->onFirstPage() ? 'opacity-20 pointer-events-none' : 'hover:border-white/20 hover:text-white' }}">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <span class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em]">
                    Página <span class="text-white">{{ $payments->currentPage() }}</span> / {{ $payments->lastPage() }}
                </span>
                <a href="{{ $payments->nextPageUrl() }}"
                   class="h-9 w-9 rounded-xl bg-[#0d121f] border border-white/5 flex items-center justify-center text-gray-500 transition-all {{ !$payments->hasMorePages() ? 'opacity-20 pointer-events-none' : 'hover:border-white/20 hover:text-white' }}">
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
