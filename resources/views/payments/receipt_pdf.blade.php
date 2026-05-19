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
    <title>Recibo de Pago #{{ $payment->id }}</title>
    <style>
        @page {
            size: 21.59cm 13.97cm;
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 0;
            padding: 1cm;
            background-color: #fff;
        }

        .receipt-container {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 25px;
            position: relative;
            height: 10.2cm;
            box-sizing: border-box;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #f3c444;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header-left { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; text-align: right; vertical-align: middle; }

        .brand-name { font-size: 18px; font-weight: 900; color: #070a13; text-transform: uppercase; }
        .brand-sub { font-size: 9px; color: #4b5563; font-weight: bold; margin-top: 2px; }
        
        .ref-label { font-size: 8px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; }
        .ref-value { font-size: 18px; font-weight: 900; color: #070a13; }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-col { display: table-cell; width: 33.3%; }
        
        .label { font-size: 7px; text-transform: uppercase; font-weight: 800; color: #9ca3af; margin-bottom: 2px; }
        .value { font-size: 10px; font-weight: bold; color: #111827; text-transform: uppercase; }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .details-table th {
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
            padding: 6px 8px;
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .details-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        .total-container {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 15px 25px;
            display: table;
            width: 100%;
            border: 1px solid #f3f4f6;
            margin-top: 5px;
        }
        .total-text { display: table-cell; vertical-align: middle; }
        .total-price { display: table-cell; text-align: right; vertical-align: middle; }
        .amount-big { font-size: 26px; font-weight: 900; color: #059669; }

        .footer-signatures {
            position: absolute;
            bottom: 30px;
            left: 30px;
            right: 30px;
            display: table;
            width: 90%;
        }
        .sign-box {
            display: table-cell;
            width: 45%;
            border-top: 1px solid #d1d5db;
            text-align: center;
            padding-top: 8px;
            font-size: 8px;
            color: #9ca3af;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        <div class="header">
            <div class="header-left">
                <div class="brand-name">DISTRIAVICOLA SOFRAQ SAS</div>
                <div class="brand-sub">
                    NIT: 901362908-3 | distrisofraq@gmail.com<br>
                    Contacto: 3132106246
                </div>
            </div>
            <div class="header-right">
                <div class="ref-label">Comprobante de Recaudo</div>
                <div class="ref-value">#REC-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-col">
                <div class="label">Cliente / Tercero</div>
                <div class="value">
                    {{ $payment->allocations->first()->invoice->customer->name ?? $payment->customer->name ?? 'Consumidor Final' }}
                </div>
            </div>
            <div class="info-col">
                <div class="label">Fecha de Operación</div>
                <div class="value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y - h:i A') }}</div>
            </div>
            <div class="info-col" style="text-align: right;">
                <div class="label">Emitido por</div>
                <div class="value">{{ Auth::user()->name }}</div>
            </div>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Concepto / Cartera</th>
                    <th>Método de Pago</th>
                    <th style="text-align: right;">Monto Aplicado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payment->allocations as $alloc)
                @php
                    // El saldo actual es lo que queda pendiente en la factura
                    $saldoRestante = $alloc->invoice->net_value; 
                    // El saldo anterior es el saldo actual + lo que se está pagando en este recibo
                    $saldoAnterior = $saldoRestante + $alloc->amount;
                @endphp
                <tr>
                    <td>
                        <div style="font-weight: 900; color: #070a13; text-transform: uppercase;">Factura #{{ $alloc->invoice->id }}</div>
                        
                    </td>
                    <td class="value">
                        @php
                            $metodo = strtolower($payment->payment_method);
                            echo match($metodo) {
                                'cash', 'efectivo' => 'EFECTIVO',
                                'transfer', 'transferencia' => 'TRANSFERENCIA',
                                'bank_transfer', 'bank_deposit' => 'TRANSF./DEPÓSITO',
                                'check', 'cheque' => 'CHEQUE',
                                default => strtoupper($payment->payment_method)
                            };
                        @endphp
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #059669; font-size: 13px;">
                         $ {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td>
                        <div style="font-weight: 900; color: #070a13; text-transform: uppercase;">Recaudo General</div>
                        <div style="font-size: 8px; color: #6b7280;">Abono global a cuenta de cartera</div>
                    </td>
                    <td class="value">
                        {{ strtoupper($payment->payment_method) }}
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #059669; font-size: 13px;">
                        $ {{ number_format($payment->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-container">
            <div class="total-text">
                <div class="label" style="color: #059669; margin-bottom: 0;">Total Recibido</div>
                <div style="font-size: 8px; color: #9ca3af;">Valor legal expresado en Pesos Colombianos (COP)</div>
            </div>
            <div class="total-price">
                <div class="amount-big">$ {{ number_format($payment->amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="footer-signatures">
            <div class="sign-box">Firma Autorizada - SOFRAQ SAS</div>
            <div style="display: table-cell; width: 10%;"></div>
            <div class="sign-box">Firma del Cliente / NIT o CC</div>
        </div>
    </div>

</body>
</html>