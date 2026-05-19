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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('invoices.index') }}"
                   class="h-9 w-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-400 hover:text-white flex items-center justify-center transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter leading-none">
                        Vista Previa <span class="text-yellow-500">PDF DIAN</span>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em] mt-1">
                        Factura #{{ $invoice->number }} · {{ $invoice->customer->name ?? '—' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('invoices.pdf', $invoice->id) }}"
               class="h-9 px-5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-black text-[10px] uppercase tracking-widest flex items-center gap-2 transition-all active:scale-95 w-fit">
                <i class="fas fa-file-pdf text-xs"></i> Descargar PDF
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div id="invoice-pdf"
            class="max-w-[850px] mx-auto bg-white p-12 shadow-2xl text-slate-800 font-sans border-t-[16px] border-yellow-500 relative overflow-hidden">

            {{-- Marca de Agua --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
                <h1 class="text-[120px] font-black -rotate-45">SOFRAQ</h1>
            </div>

            @php
                // ✅ FIX 1: null-safe en status y radian_status
                $statusMap = [
                    'draft'       => ['color' => 'zinc',    'icon' => 'fa-file-signature', 'label' => 'Pre-Factura'],
                    'signed'      => ['color' => 'blue',    'icon' => 'fa-signature',       'label' => 'Firmado'],
                    'sent'        => ['color' => 'indigo',  'icon' => 'fa-paper-plane',     'label' => 'Enviado DIAN'],
                    'accepted'    => ['color' => 'emerald', 'icon' => 'fa-check-double',    'label' => 'Aceptado'],
                    'rejected'    => ['color' => 'red',     'icon' => 'fa-times-circle',    'label' => 'Rechazado'],
                    'contingency' => ['color' => 'orange',  'icon' => 'fa-exclamation',     'label' => 'Contingencia'],
                    'voided'      => ['color' => 'slate',   'icon' => 'fa-trash-alt',       'label' => 'Anulado'],
                ];

                $paymentStatusMap = [
                    'pending' => ['color' => 'yellow',  'label' => 'Pendiente de Pago'],
                    'partial' => ['color' => 'blue',    'label' => 'Abono Parcial'],
                    'paid'    => ['color' => 'emerald', 'label' => 'Pagada'],
                ];

                $radianMap = [
                    'pending'    => ['text' => 'Pendiente Registro', 'color' => 'text-yellow-600'],
                    'registered' => ['text' => 'Registrado RADIAN',  'color' => 'text-emerald-600'],
                    'rejected'   => ['text' => 'Rechazo RADIAN',     'color' => 'text-red-600'],
                ];

                $currStatus        = $statusMap[$invoice->status ?? 'draft'] ?? $statusMap['draft'];
                $currPaymentStatus = $paymentStatusMap[$invoice->payment_status ?? 'pending'] ?? $paymentStatusMap['pending'];

                // ✅ FIX 1: radian_status null-safe
                $radianStatus = $invoice->radian_status ?? null;
                $currRadian   = $radianStatus ? ($radianMap[$radianStatus] ?? null) : null;

                // ✅ FIX 2: due_date formateado correctamente
                $dueDate = $invoice->due_date
                    ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y')
                    : \Carbon\Carbon::parse($invoice->created_at)->addDays(30)->format('d/m/Y');

                // ✅ FIX 3: tax_total es el campo correcto
                $taxTotal = $invoice->tax_total ?? 0;
                $subtotal = $invoice->subtotal ?? ($invoice->total - $taxTotal);

                // ✅ FIX 4: cufe real o placeholder
                $cufe = $invoice->cufe ?? 'CUFE_NO_GENERADO_' . strtoupper(substr(md5($invoice->id . $invoice->number), 0, 20));
            @endphp

            {{-- 1. ENCABEZADO --}}
            <div class="relative z-10 flex justify-between items-start border-b-2 border-slate-100 pb-10 mb-10">
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-slate-900 uppercase tracking-tighter leading-none mb-2">
                        DISTRIAVICOLA <span class="text-yellow-600">SOFRAQ SAS</span>
                    </h1>
                    <p class="text-md font-black text-slate-700">NIT: 901.362.908-3</p>
                    <div class="text-[11px] text-slate-500 font-bold space-y-0.5 uppercase tracking-wide">
                        <p>Responsable de IVA - Régimen Común</p>
                        <p>CARRERA 12 # 200 - 14 TORRE 2 APT</p>
                        <p>Ocaña, Norte de Santander</p>
                        <p>Cel: +57 313 2106246 | distrisofraq@gmail.com</p>
                    </div>
                </div>

                <div class="text-right">
                    <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl mb-3 shadow-xl">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-500 mb-1">Factura Electrónica de Venta</p>
                        <p class="text-2xl font-black font-mono">No. {{ $invoice->number }}</p>
                    </div>

                    <div class="text-[10px] text-slate-400 font-bold leading-relaxed border-r-4 border-yellow-500 pr-4 mb-3">
                        <p>Resolución DIAN No. 187600000000</p>
                        <p>Vigencia: 2026-01-01 hasta 2026-12-31</p>
                        <p>Rango Autorizado: DS 1 al DS 10000</p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em]">Estatus del Documento</span>

                        {{-- Badge estado documento --}}
                        <div class="relative group">
                            <div class="absolute inset-0 bg-{{ $currStatus['color'] }}-500/10 blur-xl rounded-full"></div>
                            <div class="relative flex items-center gap-3 bg-white border-2 border-{{ $currStatus['color'] }}-500/30 px-6 py-2.5 rounded-2xl shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-{{ $currStatus['color'] }}-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-{{ $currStatus['color'] }}-500"></span>
                                </span>
                                <i class="fas {{ $currStatus['icon'] }} text-{{ $currStatus['color'] }}-600 text-[10px]"></i>
                                <span class="text-xs font-black uppercase tracking-[0.15em] text-{{ $currStatus['color'] }}-700 italic">
                                    {{ $currStatus['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- ✅ Badge payment_status --}}
                        <div class="flex items-center gap-2 px-4 py-1.5 bg-{{ $currPaymentStatus['color'] }}-50 border border-{{ $currPaymentStatus['color'] }}-200 rounded-xl">
                            <span class="h-1.5 w-1.5 rounded-full bg-{{ $currPaymentStatus['color'] }}-500"></span>
                            <span class="text-[9px] font-black uppercase tracking-widest text-{{ $currPaymentStatus['color'] }}-700">
                                {{ $currPaymentStatus['label'] }}
                            </span>
                        </div>

                        {{-- RADIAN — solo si aplica --}}
                        @if($currRadian)
                            <div class="flex items-center gap-2 px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg">
                                <i class="fas fa-university text-[8px] text-slate-400"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest {{ $currRadian['color'] }}">
                                    {{ $currRadian['text'] }}
                                </span>
                            </div>
                        @endif

                        <p class="text-[7px] font-mono text-slate-400 uppercase tracking-widest">
                            DIAN_CORE_SYNC // {{ strtoupper($invoice->status ?? 'DRAFT') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. DATOS DEL CLIENTE --}}
            <div class="relative z-10 grid grid-cols-2 gap-10 mb-10 bg-slate-50/50 p-8 rounded-[2rem] border border-slate-100">
                <div>
                    <p class="text-[10px] font-black text-yellow-600 uppercase tracking-[0.3em] mb-3">Datos del Adquiriente</p>
                    <p class="text-xl font-black text-slate-900 uppercase tracking-tight mb-1">
                        {{ $invoice->customer->name ?? '—' }}
                    </p>
                    <div class="text-xs font-bold text-slate-600 space-y-1">
                        <p><span class="opacity-50">NIT/CC:</span> {{ $invoice->customer->identification_number ?? $invoice->customer->nit ?? 'N/A' }}</p>
                        <p><span class="opacity-50">Dirección:</span> {{ $invoice->customer->address ?? 'N/A' }}</p>
                        <p><span class="opacity-50">Ciudad:</span> {{ $invoice->customer->municipality_id ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="text-right space-y-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3">Detalles de Cobro</p>
                        <p class="text-xs font-bold"><strong>Fecha Emisión:</strong> <span class="font-mono">{{ $invoice->created_at->format('d/m/Y') }}</span></p>
                        <p class="text-xs font-bold"><strong>Hora Emisión:</strong> <span class="font-mono">{{ $invoice->created_at->format('H:i:s') }}</span></p>
                        {{-- ✅ FIX 2: due_date formateado --}}
                        <p class="text-xs font-bold text-yellow-700"><strong>Vencimiento:</strong> <span class="font-mono">{{ $dueDate }}</span></p>
                        <div class="pt-2">
                            <span class="bg-slate-200 text-[9px] font-black px-3 py-1 rounded-full uppercase">
                                Pago: {{ $invoice->paymentTerm->name ?? 'Contado' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. PRODUCTOS --}}
            <div class="relative z-10 mb-10">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-slate-900 text-[11px] font-black uppercase tracking-widest text-slate-900">
                            <th class="py-5 text-left">Descripción del Producto</th>
                            <th class="py-5 text-center">Cant.</th>
                            <th class="py-5 text-right">Precio Unit.</th>
                            <th class="py-5 text-right">IVA</th>
                            <th class="py-5 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($invoice->items as $item)
                        <tr class="text-sm">
                            <td class="py-5 font-black text-slate-800 uppercase tracking-tighter">{{ $item->description ?? '—' }}</td>
                            <td class="py-5 text-center font-black font-mono text-slate-600">{{ number_format($item->quantity, 0) }}</td>
                            <td class="py-5 text-right font-bold text-slate-500 font-mono">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-5 text-right font-bold text-slate-500 font-mono">${{ number_format($item->tax_amount, 2) }}</td>
                            <td class="py-5 text-right font-black text-slate-900 font-mono">${{ number_format($item->total_line, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 4. TOTALES Y PIE LEGAL --}}
            <div class="relative z-10 grid grid-cols-2 gap-16 items-end pt-10 border-t-2 border-slate-100">
                <div class="space-y-6">
                    <div class="flex items-start gap-6">
                        <div class="w-28 h-28 bg-white p-2 border-2 border-slate-900 rounded-xl flex items-center justify-center">
                            <i class="fas fa-qrcode text-7xl text-slate-900"></i>
                        </div>
                        <div class="flex-1 space-y-2">
                            <p class="text-[8px] font-black uppercase text-slate-400 tracking-widest leading-none">CUFE (Código Único de Factura Electrónica)</p>
                            {{-- ✅ FIX 4: CUFE real --}}
                            <p class="text-[10px] font-mono break-all text-slate-700 leading-tight bg-slate-100 p-2 rounded-lg border border-slate-200">
                                {{ $cufe }}
                            </p>
                        </div>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded-xl border-l-4 border-yellow-500 text-[10px] font-bold text-yellow-900 leading-relaxed">
                        "Esta factura de venta se asimila en todos sus efectos legales a una letra de cambio según el Art. 774 del Código de Comercio."
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-widest px-2">
                        <span>Subtotal Bruto</span>
                        {{-- ✅ FIX 3: usa $subtotal calculado correctamente --}}
                        <span class="font-mono text-slate-900 font-black">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-widest px-2">
                        <span>Impuestos (IVA)</span>
                        {{-- ✅ FIX 3: usa tax_total --}}
                        <span class="font-mono text-slate-900 font-black">${{ number_format($taxTotal, 2) }}</span>
                    </div>

                    {{-- Saldo pendiente si aplica --}}
                    @if(($invoice->payment_status ?? 'pending') !== 'paid' && ($invoice->balance ?? 0) > 0)
                    <div class="flex justify-between text-xs font-bold text-yellow-700 uppercase tracking-widest px-2">
                        <span>Saldo Pendiente</span>
                        <span class="font-mono font-black">${{ number_format($invoice->balance, 2) }}</span>
                    </div>
                    @endif

                    <div class="bg-slate-900 text-white p-6 rounded-[2rem] flex justify-between items-center shadow-xl">
                        <span class="text-xs font-black uppercase tracking-[0.3em] text-yellow-500">Total a Pagar</span>
                        <span class="text-2xl font-black font-mono tracking-tighter">${{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- PIE --}}
            <div class="mt-12 text-center border-t border-slate-100 pt-6">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.5em]">
                    Representación Gráfica de Factura Electrónica - Generado por Tizzilla Core
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    @media print {
        @page { size: letter; margin: 0; }
        body { background: white; }
        .no-print, header, nav { display: none !important; }
        #invoice-pdf {
            box-shadow: none !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 2cm !important;
        }
        .bg-slate-900 { background-color: #0f172a !important; -webkit-print-color-adjust: exact; }
        .text-yellow-500 { color: #eab308 !important; }
        .bg-yellow-500 { background-color: #eab308 !important; }
    }
</style>