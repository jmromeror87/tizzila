{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('payments.all') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Recibo de <span class="text-yellow-500">Pago</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        #REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }} · {{ optional($payment->created_at)->translatedFormat('d M, Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('payments.pdf', $payment->id) }}" target="_blank"
                   class="h-9 px-5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center gap-2 transition-all text-[10px] font-black uppercase tracking-widest">
                    <i class="fas fa-file-pdf text-emerald-500 text-xs"></i> PDF
                </a>
                <form action="{{ route('payments.whatsapp.send', $payment->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="h-9 px-5 rounded-xl bg-[#25D366] hover:bg-[#128C7E] text-white font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.228 3.481 8.405c0 6.555-5.332 11.89-11.888 11.89-2.015 0-3.991-.511-5.741-1.486L0 24zm6.535-3.56c1.554.922 3.153 1.408 4.75 1.408 5.148 0 9.338-4.19 9.338-9.339 0-2.491-.97-4.832-2.731-6.593S13.832 3.19 11.341 3.19c-5.148 0-9.338 4.19-9.338 9.34 0 1.696.467 3.351 1.353 4.792L2.302 21.72l4.29-1.28zm10.596-6.111c-.26-.13-1.534-.757-1.772-.843-.238-.086-.412-.13-.585.13s-.672.843-.823 1.017c-.151.173-.303.195-.563.065-.26-.13-1.097-.404-2.09-1.288-.772-.69-1.293-1.542-1.444-1.803-.152-.26-.016-.4.114-.53.117-.117.26-.303.39-.455.13-.152.173-.26.26-.433.087-.173.043-.325-.022-.455-.065-.13-.585-1.408-.802-1.928-.211-.51-.423-.44-.585-.448-.152-.008-.325-.01-.499-.01-.173 0-.455.065-.693.325-.238.26-.91.888-.91 2.165 0 1.277.932 2.512 1.062 2.685.13.173 1.834 2.8 4.444 3.927.62.268 1.104.428 1.481.548.623.198 1.19.17 1.637.104.498-.074 1.534-.627 1.75-.1.217-.607.217-1.127.152-1.213-.065-.086-.238-.13-.498-.26z"/></svg>
                        WhatsApp
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-4 space-y-4 max-w-3xl mx-auto">

        {{-- Meta info --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Referencia</p>
                <p class="text-sm font-black text-white">#REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Fecha</p>
                <p class="text-sm font-black text-white">{{ optional($payment->created_at)->translatedFormat('d M, Y') }}</p>
                <p class="text-[9px] text-gray-600 uppercase">{{ optional($payment->created_at)->format('H:i') }}</p>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Método</p>
                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border bg-yellow-500/10 text-yellow-500 border-yellow-500/20">
                    {{ $payment->payment_method }}
                </span>
            </div>
            <div class="bg-[#0d121f] border border-white/5 rounded-2xl p-4">
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Operador</p>
                <p class="text-[10px] font-black text-white uppercase">{{ $payment->user->name ?? 'Sistema' }}</p>
            </div>
        </div>

        {{-- Desglose de aplicación --}}
        <div class="bg-[#0d121f] border border-white/5 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-500">Desglose de Aplicación</h3>
            </div>
            <div class="p-4 space-y-2">
                @forelse($payment->allocations as $alloc)
                    <div class="flex justify-between items-center bg-black/20 px-4 py-3 rounded-xl border border-white/5 hover:border-yellow-500/20 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-black/40 border border-white/5 flex items-center justify-center text-gray-600 group-hover:text-yellow-500 transition-colors">
                                <i class="fas fa-file-invoice-dollar text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-white uppercase">Factura #{{ $alloc->invoice->id ?? '—' }}</p>
                                <p class="text-[9px] text-gray-600 font-bold uppercase">
                                    {{ optional(optional($alloc->invoice)->customer)->name ?? 'Consumidor Final' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-gray-600 uppercase mb-0.5">Aplicado</p>
                            <p class="text-base font-black text-emerald-500">${{ number_format($alloc->amount_applied, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center bg-white/[0.01] rounded-xl border border-dashed border-white/10">
                        <i class="fas fa-link-slash text-2xl text-gray-800 block mb-2"></i>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-600">Sin facturas vinculadas</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Total --}}
        <div class="bg-[#0d121f] border border-emerald-500/10 rounded-2xl p-5 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Total Recaudado</p>
                <p class="text-[9px] text-gray-600 font-bold uppercase">Pesos Colombianos (COP)</p>
            </div>
            <p class="text-4xl font-black text-emerald-500 tracking-tighter">
                ${{ number_format($payment->amount, 0, ',', '.') }}
            </p>
        </div>

        {{-- Audit footer --}}
        <div class="flex items-center justify-between text-[9px] font-bold text-gray-700 uppercase tracking-widest px-1">
            <span>Registro: {{ optional($payment->created_at)->format('d/m/Y H:i') }}</span>
            <span>Tizzila OS · Comprobante de Pago</span>
        </div>

    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            .py-4, .py-4 * { visibility: visible; }
            .py-4 { position: absolute; left: 0; top: 0; width: 100%; }
            button, a { display: none !important; }
        }
    </style>
</x-app-layout>
