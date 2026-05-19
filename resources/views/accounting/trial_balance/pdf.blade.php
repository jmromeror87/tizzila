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
<html>
<head>
    <meta charset="utf-8">
    <title>Balance de Prueba - Tizzila</title>
    <style>
        /* Estética corporativa para PDF */
        @page { margin: 1.5cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px; 
            color: #1a1a1a; 
            line-height: 1.4;
        }
        
        /* Encabezado */
        .header { margin-bottom: 30px; border-bottom: 2px solid #f3c444; padding-bottom: 10px; }
        .company-name { font-size: 18px; font-weight: bold; color: #000; text-transform: uppercase; letter-spacing: 1px; }
        .report-title { font-size: 14px; color: #666; margin-top: 5px; text-transform: uppercase; }
        .meta-data { float: right; text-align: right; font-size: 9px; color: #888; margin-top: -40px; }

        /* Tabla Principal */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { 
            background-color: #f8f9fa; 
            color: #333; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 9px;
            padding: 10px 8px;
            border-bottom: 1px solid #333;
            text-align: left;
        }
        td { 
            padding: 8px; 
            border-bottom: 1px solid #eee; 
            vertical-align: middle;
        }
        
        /* Clases de utilidad */
        .right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #777; font-size: 8px; }
        .bg-totals { background-color: #000; color: #fff; }
        
        /* Colores contables */
        .debit { color: #000; }
        .credit { color: #d93025; } /* Un rojo serio para el crédito en papel */

        /* Footer del PDF */
        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            font-size: 8px; 
            text-align: center; 
            color: #aaa; 
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company-name">DISTRIAVICOLA<span style="color: #f3c444;">SOFRAQ SAS<</span></div>
        <div class="nit">NIT: 901362908-3</div>
        <div class="email">Email: distrisofraq@gmail.com</div>
        <div class="report-title">Balance de Comprobación</div>
        <div class="meta-data">
            Generado el: {{ now()->format('d/m/Y H:i') }}<br>
            Periodo: {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}<br>
            Usuario: {{ auth()->user()->name ?? 'Sistema' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="45%">Cuenta Contable / Código</th>
                <th width="18%" class="right">Débito</th>
                <th width="18%" class="right">Crédito</th>
                <th width="19%" class="right">Saldo Final</th>
            </tr>
        </thead>

        <tbody>
            @foreach($lines as $line)
            <tr>
                <td>
                    <span class="font-bold uppercase">{{ $line->account->name }}</span><br>
                    <span class="text-muted">CÓD: {{ $line->account->code }}</span>
                </td>
                <td class="right debit">
                    {{ number_format($line->total_debit, 0, ',', '.') }}
                </td>
                <td class="right credit">
                    {{ number_format($line->total_credit, 0, ',', '.') }}
                </td>
                <td class="right font-bold">
                    {{ number_format(abs($line->balance), 0, ',', '.') }}
                    <span class="text-muted">{{ $line->balance >= 0 ? 'DB' : 'CR' }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="bg-totals">
                <td class="right font-bold uppercase" style="padding: 12px;">Totales de Control</td>
                <td class="right font-bold" style="padding: 12px;">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                <td class="right font-bold" style="padding: 12px;">{{ number_format($totalCredit, 0, ',', '.') }}</td>
                <td class="right font-bold" style="padding: 12px; background-color: #f3c444; color: #000;">
                    DIF: {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- Cuadro de Validación --}}
    <div style="margin-top: 30px; padding: 15px; border: 1px solid #eee; background-color: #fafafa;">
        <span class="font-bold uppercase" style="font-size: 9px;">Estado de Validación</span>
        @if(round($totalDebit, 2) == round($totalCredit, 2))
            <span style="color: #2e7d32; font-weight: bold;">BALANCE CUADRADO - Partida doble verificada correctamente.</span>
        @else
            <span style="color: #d32f2f; font-weight: bold;">DESCUADRE DETECTADO - Revisar asientos del periodo.</span>
        @endif
    </div>

    <div class="footer">
        Este documento es un reporte contable generado automáticamente por Tizzila ERP.
    </div>

</body>
</html>