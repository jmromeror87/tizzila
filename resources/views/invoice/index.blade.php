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
                    Historial de <span class="text-yellow-500">Facturación</span>
                </h2>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                    Archivo Central · {{ number_format($invoices->total()) }} Registros
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-xl">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest">Sistema en Línea</span>
                </div>
                <a href="{{ route('invoices.create') }}"
                   class="h-9 px-5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all active:scale-95">
                    <i class="fas fa-plus text-xs"></i> Nueva Factura
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4">

        @if(session('success'))
            <div class="px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 py-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        {{-- FILTROS --}}
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4 grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs pointer-events-none"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por folio, cliente o documento..."
                           class="w-full bg-black/30 border border-white/10 rounded-xl py-2.5 pl-10 pr-4 text-[11px] font-bold text-white focus:outline-none focus:border-yellow-500/50 transition-colors placeholder:text-zinc-700">
                </div>
                <div class="md:col-span-3">
                    <select name="payment_status"
                            class="w-full appearance-none bg-black/30 border border-white/10 rounded-xl py-2.5 px-4 text-[11px] font-bold text-zinc-400 focus:outline-none focus:border-yellow-500/50 transition-colors cursor-pointer">
                        <option value="">Todos los estados</option>
                        <option value="pending"  @selected(request('payment_status') == 'pending')>Pendientes</option>
                        <option value="partial"  @selected(request('payment_status') == 'partial')>Abonadas</option>
                        <option value="paid"     @selected(request('payment_status') == 'paid')>Pagadas</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="w-full bg-black/30 border border-white/10 rounded-xl py-2.5 px-4 text-[11px] font-bold text-zinc-400 focus:outline-none focus:border-yellow-500/50 transition-colors">
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-yellow-500 hover:bg-yellow-400 text-black rounded-xl transition-all active:scale-95 flex items-center justify-center">
                        <i class="fas fa-filter text-xs"></i>
                    </button>
                    <a href="{{ route('invoices.index') }}"
                       class="flex-1 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 rounded-xl transition-all flex items-center justify-center">
                        <i class="fas fa-times text-xs"></i>
                    </a>
                </div>
            </div>
        </form>

        {{-- TABLA --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em]">Estado</th>
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em]">Folio</th>
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em]">Cliente</th>
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Fecha</th>
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Total</th>
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Saldo</th>
                            <th class="px-5 py-4 text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($invoices as $invoice)
                            @php
                                $dotColor = match($invoice->payment_status) {
                                    'paid'    => 'bg-emerald-500 shadow-[0_0_8px_#10b981]',
                                    'partial' => 'bg-blue-500',
                                    'pending' => 'bg-yellow-500',
                                    default   => 'bg-zinc-600',
                                };
                                $statusLabel = match($invoice->payment_status) {
                                    'paid'    => 'Pagada',
                                    'partial' => 'Abonada',
                                    'pending' => 'Pendiente',
                                    default   => '—',
                                };
                            @endphp
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full {{ $dotColor }}"></span>
                                        <span class="text-[9px] font-black uppercase text-zinc-400 tracking-widest">{{ $statusLabel }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-xs font-mono font-black text-white group-hover:text-yellow-500 transition-colors">
                                        #{{ str_pad($invoice->number, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-[11px] font-black text-zinc-300 uppercase">{{ $invoice->customer->name ?? '—' }}</p>
                                    <p class="text-[9px] text-zinc-600 uppercase tracking-widest">NIT: {{ $invoice->customer->identification_number ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <span class="text-[10px] font-black text-zinc-500 font-mono">{{ $invoice->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <span class="text-sm font-black text-white font-mono">${{ number_format($invoice->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($invoice->balance <= 0)
                                        <span class="text-[10px] font-black text-emerald-500 font-mono">$0</span>
                                    @elseif($invoice->payment_status === 'partial')
                                        <span class="text-[10px] font-black text-blue-400 font-mono">${{ number_format($invoice->balance, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-[10px] font-black text-yellow-500 font-mono">${{ number_format($invoice->balance, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($invoice->payment_status !== 'paid')
                                            <a href="{{ route('invoices.payments.index', $invoice) }}"
                                               class="h-8 px-3 bg-yellow-500 hover:bg-yellow-400 text-black rounded-lg flex items-center gap-1.5 text-[9px] font-black uppercase tracking-tight transition-all active:scale-95">
                                                <i class="fas fa-wallet text-[9px]"></i> Pagos
                                            </a>
                                        @else
                                            <span class="h-8 px-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-lg flex items-center gap-1.5 text-[9px] font-black uppercase">
                                                <i class="fas fa-check text-[9px]"></i> Pagada
                                            </span>
                                        @endif
                                        <a href="{{ route('invoices.show', $invoice) }}"
                                           class="h-8 w-8 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-500 hover:text-white rounded-lg flex items-center justify-center transition-all">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <i class="fas fa-file-invoice text-4xl text-zinc-800 block mb-3"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-600">Sin facturas registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            @if($invoices->hasPages())
                <div class="px-5 py-4 bg-white/[0.01] border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest">
                        Mostrando <span class="text-white">{{ $invoices->firstItem() }}</span>–<span class="text-white">{{ $invoices->lastItem() }}</span>
                        de <span class="text-yellow-500">{{ $invoices->total() }}</span>
                    </p>
                    <div class="pagination-wrap">{{ $invoices->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<style>
    .pagination-wrap nav > div:last-child { display: flex; gap: 0.375rem; background: transparent; border: none; }
    .pagination-wrap nav > div:first-child { display: none; }
    .pagination-wrap span[aria-current="page"] span {
        background: #eab308; border-color: #eab308; color: black;
        border-radius: 0.5rem; padding: 0.375rem 0.75rem; font-size: 0.7rem; font-weight: 900;
    }
    .pagination-wrap a, .pagination-wrap span {
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        color: #71717a; border-radius: 0.5rem; padding: 0.375rem 0.75rem;
        font-size: 0.7rem; font-weight: 900; text-transform: uppercase; transition: all .15s;
    }
    .pagination-wrap a:hover { background: rgba(255,255,255,0.08); color: white; }
</style>
