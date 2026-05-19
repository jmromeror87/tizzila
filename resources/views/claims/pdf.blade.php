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
    <style>
        @page { margin: 1.5cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.5;
        }
        /* Cabecera Tizzila Industrial */
        .header {
            border-bottom: 4px solid #eab308;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: -1px;
            color: #111827;
        }
        .meta-table { width: 100%; margin-top: 5px; }
        .meta-text {
            color: #666;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 1px;
        }

        /* Secciones */
        .section-title {
            background: #111827;
            color: white;
            padding: 6px 12px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            margin-top: 25px;
        }

        .info-grid { width: 100%; margin-top: 10px; border-collapse: collapse; }
        .info-box {
            padding: 12px;
            border: 1px solid #e4e4e7;
            background: #fafafa;
        }
        .label {
            font-size: 8px;
            color: #71717a;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }
        .value { font-size: 12px; font-weight: bold; color: #111827; }

        /* Tabla de Auditoría */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background: #f4f4f5;
            color: #111827;
            text-align: left;
            padding: 10px;
            font-size: 9px;
            text-transform: uppercase;
            border-bottom: 2px solid #e4e4e7;
        }
        .table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 11px;
        }

        /* Argumentación */
        .argument-box {
            padding: 15px;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            margin-top: 10px;
            font-size: 11px;
            text-align: justify;
            color: #92400e;
            line-height: 1.6;
        }

        /* Galería de Evidencias */
        .evidence-table {
            width: 100%;
            margin-top: 15px;
        }
        .photo-card {
            width: 31%;
            padding: 5px;
            vertical-align: top;
        }
        .img-wrapper {
            border: 1px solid #e4e4e7;
            padding: 4px;
            background: white;
        }
        .img-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }
        .photo-caption {
            font-size: 8px;
            color: #71717a;
            text-align: center;
            margin-top: 6px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        .text-red { color: #dc2626; }
        .text-emerald { color: #059669; }
        .bold { font-weight: bold; }
    </style>
</head>

<body>

    <div class="header">
        <table class="meta-table">
            <tr>
                <td>
                    <div class="title">REPORTE DE <span style="color: #eab308;">RECLAMO</span></div>
                    <div class="meta-text">ID EXPEDIENTE: #{{ str_pad($claim->id, 6, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="meta-text">FECHA EMISIÓN: {{ now()->format('d/m/Y | H:i') }}</div>
                    <div class="meta-text">ESTADO: <span class="bold">{{ strtoupper($claim->status) }}</span></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Información del Despacho</div>
    <table class="info-grid">
        <tr>
            <td class="info-box" width="50%">
                <span class="label">Proveedor</span>
                <div class="value">{{ $claim->provider->business_name }}</div>
                <div style="font-size: 9px; color: #71717a;">{{ $claim->provider->email }}</div>
            </td>
            <td class="info-box" width="50%">
                <span class="label">Ruta Operativa</span>
                <div class="value">RUTA #{{ $claim->confirmation->dispatch_route_id ?? 'N/A' }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Resultados de Auditoría Física</div>
    <table class="table">
        <thead>
            <tr>
                <th>Programado</th>
                <th>Recibido</th>
                <th>Mortalidad</th>
                <th>Costo Unit.</th>
                <th style="text-align: right;">Total Reclamo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($claim->scheduled_quantity) }} und.</td>
                <td>{{ number_format($claim->received_quantity) }} und.</td>
                <td class="text-red bold">{{ number_format($claim->dead_quantity) }} MUERTOS</td>
                <td>${{ number_format($claim->unit_price, 2) }}</td>
                <td style="text-align: right;" class="text-emerald bold">
                    ${{ number_format($claim->claim_amount, 2) }} USD
                </td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Argumentación Jurídica y Técnica</div>
    <div class="argument-box">
        <strong>CONSTANCIA DE HALLAZGO:</strong> Durante la inspección física obligatoria de la 
        <strong>Ruta #{{ $claim->confirmation->dispatch_route_id }}</strong>, se ha verificado técnicamente 
        una mortalidad de <strong>{{ $claim->dead_quantity }} unidades</strong>. Este hallazgo ha sido 
        documentado por el equipo de auditoría de <strong>Tizzila</strong> en tiempo real, validando que 
        la pérdida ocurrió previo a la descarga final o debido a condiciones de transporte inadecuadas. 
        Las imágenes presentadas en este documento sirven como respaldo probatorio de la discrepancia 
        detectada, exigiendo la compensación inmediata de <strong>${{ number_format($claim->claim_amount, 2) }}</strong> 
        bajo los términos de calidad acordados.
    </div>

    @if($claim->notes)
        <div class="section-title">Observaciones Adicionales</div>
        <div style="padding: 10px; border: 1px solid #e4e4e7; margin-top: 5px; font-style: italic;">
            "{{ $claim->notes }}"
        </div>
    @endif

    <div class="section-title">Panel de Evidencias (Registro Fotográfico)</div>
    @if($claim->evidences->count())
        <table class="evidence-table">
            @foreach($claim->evidences->chunk(3) as $chunk)
                <tr>
                    @foreach($chunk as $evidence)
                        @php
                            $path = storage_path('app/public/'.$evidence->image_path);
                            $base64 = null;
                            if(file_exists($path)){
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            }
                        @endphp
                        <td class="photo-card">
                            <div class="img-wrapper">
                                @if($base64)
                                    <img src="{{ $base64 }}">
                                @else
                                    <div style="height: 100px; background: #eee; text-align: center; padding-top: 40px;">Error carga</div>
                                @endif
                            </div>
                            <div class="photo-caption">Evidencia Item #{{ $loop->parent->index * 3 + $loop->iteration }}</div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    @else
        <p style="color: #999; font-style: italic; padding: 15px; text-align: center;">
            No se han adjuntado evidencias visuales a este reclamo.
        </p>
    @endif

    <div class="footer">
        Este documento es un registro oficial de auditoría. Prohibida su alteración.
        <strong>Operaciones Tizzila © {{ date('Y') }}</strong>
    </div>

</body>
</html>