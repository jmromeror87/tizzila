{{--
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
--}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura {{ $invoice->number }}</title>
    <style>
        @page { margin: 0; size: letter; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #1e293b;
        }

        .invoice-container {
            width: 700px;
            margin: auto;
            padding: 40px;
            border-top: 15px solid #eab308;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 350px;
            left: 50px;
            font-size: 110px;
            font-weight: 900;
            color: #f8fafc;
            transform: rotate(-45deg);
            z-index: -1;
            text-transform: uppercase;
        }

        .f-black   { font-weight: 900; }
        .f-bold    { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .text-yellow { color: #ca8a04; }
        .text-slate  { color: #64748b; }

        .header-table { width: 100%; border-bottom: 2px solid #f1f5f9; padding-bottom: 25px; margin-bottom: 30px; }
        .title { font-size: 32px; line-height: 0.9; letter-spacing: -1.5px; }

        .badge-dark {
            background-color: #0f172a;
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 18px;
            text-align: right;
            display: inline-block;
        }

        .info-box {
            background-color: #f8fafc;
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #f1f5f9;
        }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th {
            border-bottom: 4px solid #0f172a;
            padding: 12px 5px;
            font-size: 10px;
            font-weight: 900;
            text-align: left;
        }
        .items-table td {
            padding: 15px 5px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        .total-card {
            background-color: #0f172a;
            color: #ffffff;
            padding: 20px 30px;
            border-radius: 30px;
            text-align: right;
            border-bottom: 6px solid #000000;
        }

        .legal-box {
            background-color: #fefce8;
            border-left: 5px solid #eab308;
            padding: 12px;
            font-size: 9px;
            font-weight: bold;
            color: #854d0e;
            border-radius: 0 10px 10px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            border: 2px solid #ddd;
            background: #fff;
            font-weight: 900;
            font-size: 10px;
            text-transform: uppercase;
            font-style: italic;
        }

        .payment-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 10px;
            font-weight: 900;
            font-size: 9px;
            text-transform: uppercase;
            margin-top: 6px;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="watermark">SOFRAQ</div>

    @php
        // ✅ FIX 1: null-safe en status y radian_status
        $statusMap = [
            'draft'       => ['color' => '#94a3b8', 'label' => 'Pre-Factura'],
            'signed'      => ['color' => '#3b82f6', 'label' => 'Firmado'],
            'sent'        => ['color' => '#6366f1', 'label' => 'Enviado DIAN'],
            'accepted'    => ['color' => '#10b981', 'label' => 'Aceptado'],
            'rejected'    => ['color' => '#ef4444', 'label' => 'Rechazado'],
            'contingency' => ['color' => '#f59e0b', 'label' => 'Contingencia'],
            'voided'      => ['color' => '#475569', 'label' => 'Anulado'],
        ];

        // ✅ FIX 4: payment_status para mostrar estado de cobro
        $paymentStatusMap = [
            'pending' => ['color' => '#eab308', 'bg' => '#fefce8', 'label' => 'Pendiente de Pago'],
            'partial' => ['color' => '#3b82f6', 'bg' => '#eff6ff', 'label' => 'Abono Parcial'],
            'paid'    => ['color' => '#10b981', 'bg' => '#f0fdf4', 'label' => 'Pagada'],
        ];

        $currStatus        = $statusMap[$invoice->status ?? 'draft'] ?? $statusMap['draft'];
        $currPaymentStatus = $paymentStatusMap[$invoice->payment_status ?? 'pending'] ?? $paymentStatusMap['pending'];

        // ✅ FIX 1: null-safe en radian_status
        $radianMap = [
            'pending'    => ['text' => 'Pendiente Registro', 'color' => '#eab308'],
            'registered' => ['text' => 'Registrado RADIAN',  'color' => '#10b981'],
            'rejected'   => ['text' => 'Rechazo RADIAN',     'color' => '#ef4444'],
        ];

        $radianStatus = $invoice->radian_status ?? null;
        $currRadian   = $radianStatus ? ($radianMap[$radianStatus] ?? null) : null;

        // ✅ FIX 2: due_date formateado correctamente
        $dueDate = $invoice->due_date
            ? \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y')
            : \Carbon\Carbon::parse($invoice->created_at)->addDays(30)->format('d/m/Y');

        // ✅ FIX 3: cufe null-safe
        $cufe = $invoice->cufe ?? 'CUFE_NO_GENERADO';
    @endphp

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td width="60%" style="vertical-align: top;">
                <h1 class="title f-black uppercase">
                    DISTRIAVICOLA <span class="text-yellow">SOFRAQ SAS</span>
                </h1>
                <div class="f-black" style="font-size:16px; margin-top:5px;">NIT: 901.362.908-3</div>
                <div class="text-slate f-bold uppercase" style="font-size:9px; margin-top:8px; line-height:1.4;">
                    Responsable de IVA - Régimen Común<br>
                    CARRERA 12 # 200 - 14 TORRE 2 APT<br>
                    Ocaña, Norte de Santander<br>
                    Cel: +57 313 2106246 | distrisofraq@gmail.com
                </div>
            </td>
            <td width="40%" style="vertical-align: top; text-align: right;">
                <div class="badge-dark">
                    <div style="color:#eab308; font-size:9px; font-weight:900; letter-spacing:2px; margin-bottom:5px;">FACTURA ELECTRÓNICA DE VENTA</div>
                    <div class="f-black" style="font-size:22px; font-family:monospace;">No. {{ $invoice->number }}</div>
                </div>

                <div style="font-size:9px; margin-top:10px; padding-right:10px; border-right:3px solid #eab308; color:#64748b; font-weight:700;">
                    <div>Resolución DIAN No. 187600000000</div>
                    <div>Vigencia: 2026-01-01 hasta 2026-12-31</div>
                    <div>Rango Autorizado: DS 1 al DS 10000</div>

                    <div style="margin-top:10px;">
                        <div style="font-size:9px; font-weight:900; color:#94a3b8; margin-bottom:6px; text-transform:uppercase; letter-spacing:1px;">
                            Estado del Documento
                        </div>

                        <div class="status-badge" style="border-color: {{ $currStatus['color'] }};">
                            <span style="font-size:11px; color: {{ $currStatus['color'] }};">{{ $currStatus['label'] }}</span>
                        </div>

                        {{-- ✅ FIX 4: estado de pago --}}
                        <div class="payment-badge" style="background: {{ $currPaymentStatus['bg'] }}; color: {{ $currPaymentStatus['color'] }}; border: 1px solid {{ $currPaymentStatus['color'] }};">
                            {{ $currPaymentStatus['label'] }}
                        </div>

                        @if($currRadian)
                            <div style="margin-top:6px; font-size:8px; font-weight:900; color: {{ $currRadian['color'] }};">
                                {{ $currRadian['text'] }}
                            </div>
                        @endif

                        <div style="margin-top:8px; font-size:8px; color:#94a3b8; font-family:monospace;">
                            DIAN_CORE_SYNC // {{ strtoupper($invoice->status ?? 'DRAFT') }}
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- CLIENTE --}}
    <div class="info-box">
        <table width="100%">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <div style="font-size:9px; font-weight:900; color:#ca8a04; letter-spacing:2px;">Datos del Adquiriente</div>
                    <div class="f-black uppercase" style="font-size:18px; margin:4px 0;">{{ $invoice->customer->name ?? '—' }}</div>
                    <div class="text-slate f-bold" style="font-size:11px;">
                        NIT/CC: {{ $invoice->customer->identification_number ?? $invoice->customer->nit ?? 'N/A' }}<br>
                        Dirección: {{ $invoice->customer->address ?? 'N/A' }}<br>
                        Ciudad: {{ $invoice->customer->municipality_id ?? 'N/A' }}
                    </div>
                </td>
                <td width="40%" style="vertical-align: top; text-align:right;">
                    <div style="font-size:9px; font-weight:900; color:#64748b; letter-spacing:2px; text-transform:uppercase;">Detalles de Cobro</div>
                    <div style="font-size:11px; font-weight:bold; line-height:1.6; margin-top:6px;">
                        Fecha Emisión: <span style="font-family:monospace;">{{ $invoice->created_at->format('d/m/Y') }}</span><br>
                        Hora Emisión: <span style="font-family:monospace;">{{ $invoice->created_at->format('H:i:s') }}</span><br>
                        {{-- ✅ FIX 2: due_date formateado --}}
                        <span style="color:#ca8a04; font-weight:900;">Vencimiento: <span style="font-family:monospace;">{{ $dueDate }}</span></span>
                    </div>
                    <div style="margin-top:8px;">
                        <span style="background:#e2e8f0; padding:3px 10px; border-radius:10px; font-size:8px; font-weight:900; text-transform:uppercase;">
                            PAGO: {{ $invoice->paymentTerm->name ?? 'CONTADO' }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- PRODUCTOS --}}
    <table class="items-table">
        <thead>
            <tr class="uppercase">
                <th width="45%">Descripción del Producto</th>
                <th width="10%" style="text-align:center;">Cant.</th>
                <th width="15%" style="text-align:right;">Precio Unit.</th>
                <th width="10%" style="text-align:right;">IVA</th>
                <th width="20%" style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td class="f-black uppercase">{{ $item->description ?? '—' }}</td>
                <td style="text-align:center;" class="f-black text-slate">{{ number_format($item->quantity, 0) }}</td>
                <td style="text-align:right; font-family:monospace;" class="f-bold text-slate">${{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align:right; font-family:monospace;" class="f-bold text-slate">${{ number_format($item->tax_amount, 2) }}</td>
                <td style="text-align:right; font-family:monospace;" class="f-black">${{ number_format($item->total_line, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALES --}}
    <table width="100%">
        <tr>
            <td width="55%" style="vertical-align:bottom;">
                <table width="100%" style="margin-bottom:15px;">
                    <tr>
                        <td width="90px">
                            <div style="border:2px solid #0f172a; padding:8px; border-radius:12px; text-align:center;">
                                <div style="font-size:7px; color:#94a3b8; font-weight:900;">CÓDIGO QR<br>DIAN</div>
                            </div>
                        </td>
                        <td style="padding-left:12px; vertical-align:top;">
                            <div style="font-size:8px; font-weight:900; color:#64748b; margin-bottom:4px;">CUFE (Código Único de Factura Electrónica)</div>
                            {{-- ✅ FIX 3: cufe null-safe --}}
                            <div style="font-size:8px; font-family:monospace; background:#f1f5f9; padding:6px; border-radius:6px; word-break:break-all; color:#475569;">
                                {{ $cufe }}
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="legal-box">
                    "Esta factura de venta se asimila en todos sus efectos legales a una letra de cambio según el Art. 774 del Código de Comercio."
                </div>
            </td>

            <td width="45%" style="padding-left:30px;">
                <table width="100%" style="font-size:11px; font-weight:bold; color:#64748b; margin-bottom:10px;">
                    <tr>
                        <td class="uppercase">Subtotal Bruto</td>
                        <td style="text-align:right; font-family:monospace;" class="f-black">${{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="uppercase" style="padding:5px 0;">Impuestos (IVA)</td>
                        <td style="text-align:right; font-family:monospace;" class="f-black">${{ number_format($invoice->tax_total ?? 0, 2) }}</td>
                    </tr>
                    {{-- ✅ FIX 4: mostrar saldo pendiente si no está pagada --}}
                    @if(($invoice->payment_status ?? 'pending') !== 'paid' && ($invoice->balance ?? 0) > 0)
                    <tr>
                        <td class="uppercase" style="padding:5px 0; color:#eab308;">Saldo Pendiente</td>
                        <td style="text-align:right; font-family:monospace; color:#eab308;" class="f-black">
                            ${{ number_format($invoice->balance, 2) }}
                        </td>
                    </tr>
                    @endif
                </table>

                <div class="total-card">
                    <table width="100%">
                        <tr>
                            <td style="text-align:left;">
                                <div style="color:#eab308; font-size:9px; font-weight:900; letter-spacing:2px;">
                                    TOTAL A<br>PAGAR
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div class="f-black" style="font-size:24px; font-family:monospace;">${{ number_format($invoice->total, 2) }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top:50px; text-align:center; border-top:1px solid #f1f5f9; padding-top:15px;">
        <p class="text-slate f-black uppercase" style="font-size:8px; letter-spacing:3px;">
            Representación Gráfica de Factura Electrónica — Generado por Tizzilla Core
        </p>
    </div>
</div>
</body>
</html>