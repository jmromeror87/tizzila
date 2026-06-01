<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice\Invoice;
use App\Models\Customer\Customer;
use App\Services\Invoice\InvoiceService;

class InvoiceImportController extends Controller
{
    public function form()
    {
        return view('invoice.import');
    }

    public function template()
    {
        $rows = [
            ['FECHA', 'NUMERO_FACTURA', 'NIT_CLIENTE', 'NOMBRE_CLIENTE', 'ZONA', 'TIPO_PRODUCTO', 'LINEA', 'OBSERVACION', 'CANTIDAD', 'PRECIO_COMPRA', 'PRECIO_VENTA', 'TOTAL_VENTA', 'FACTURADO'],
            ['2026-01-09', 'FVE6826', '88032951',   'JUAN CARLOS CAMARGO JEREZ',        'PAMPLONA',   'POLLITO', 'BROILER', 'NINGUNA',  '3500', '2620', '3300', '11550000', 'SI'],
            ['2026-01-09', 'FVE6827', '77023217',   'NELSON CASTRO RUEDAS',             'PAMPLONITA', 'POLLITO', 'BROILER', 'NINGUNA',  '2000', '2620', '3300',  '6600000', 'SI'],
            ['2026-01-16', 'FVE6840', '901885356',  'DISTRIBUIDORA AGROPECUARIA GOMEZ', 'CUCUTA',     'POLLITA', 'LOHMAN',  'DESPIQUE',  '500', '4490', '4970',  '2485000', 'SI'],
        ];

        $output = fopen('php://temp', 'w');
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_facturas_tizzila.csv"',
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->parseCsv($request->file('csv_file')->getRealPath());

        // Solo las facturadas (SI) van a este módulo
        $rows = array_filter($rows, fn($r) => $r['facturado'] === 'SI');
        $rows = array_values($rows);

        // Agrupar por número para mostrar resumen
        $invoices = collect($rows)->groupBy('invoice_number')->map(function ($items) {
            return [
                'invoice_number' => $items->first()['invoice_number'],
                'issue_date'     => $items->first()['issue_date'],
                'nit_cliente'    => $items->first()['nit_cliente'],
                'nombre_cliente' => $items->first()['nombre_cliente'],
                'total'          => $items->sum('total_linea'),
                'items_count'    => $items->count(),
                'items'          => $items->toArray(),
            ];
        })->values()->toArray();

        $totalVenta = collect($invoices)->sum('total');

        return view('invoice.import-preview', compact('invoices', 'rows', 'totalVenta'));
    }

    public function import(Request $request, InvoiceService $service)
    {
        $rows      = $request->input('rows', []);
        $companyId = Auth::user()->company_id ?? DB::table('companies')->value('id') ?? 1;

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        // Agrupar por número de factura
        $grouped = collect($rows)
            ->filter(fn($r) => !empty($r['import']) && $r['import'] == '1')
            ->groupBy('invoice_number');

        foreach ($grouped as $number => $items) {
            try {
                $number = trim($number);

                if (Invoice::where('number', $number)->exists()) {
                    $skipped++;
                    continue;
                }

                $first      = $items->first();
                $nit        = trim($first['nit_cliente'] ?? '');
                $nombre     = trim($first['nombre_cliente'] ?? 'CLIENTE SIN NOMBRE');

                // Buscar o crear cliente
                $customer = Customer::where('identification_number', $nit)->first();
                if (!$customer && $nit) {
                    $defaultMunicipality = DB::table('municipalities')->value('id') ?? 1;
                    $customer = Customer::create([
                        'identification_number' => $nit,
                        'name'                  => $nombre,
                        'type_document_id'      => 31,
                        'type_organization_id'  => 2,
                        'type_regime_id'        => 49,
                        'type_liability_id'     => 'R-99-PN',
                        'municipality_id'       => $defaultMunicipality,
                        'payment_term_id'       => 1,
                        'credit_limit'          => 0,
                    ]);
                }

                if (!$customer) {
                    $errors[] = "Factura {$number}: sin NIT válido.";
                    continue;
                }

                // Construir ítems
                $invoiceItems = $items->map(function ($item) {
                    $cantidad    = (float) str_replace([',', ' '], '', $item['cantidad'] ?? 1);
                    $precioVenta = (float) str_replace([',', ' ', '$'], '', $item['precio_venta'] ?? 0);
                    $totalLinea  = (float) str_replace([',', ' ', '$'], '', $item['total_linea'] ?? ($cantidad * $precioVenta));

                    if ($cantidad > 0 && $precioVenta == 0 && $totalLinea > 0) {
                        $precioVenta = $totalLinea / $cantidad;
                    }

                    $descripcion = strtoupper(trim($item['tipo_producto'] ?? 'POLLITO'));
                    if (!empty($item['linea'])) $descripcion .= ' ' . $item['linea'];
                    if (!empty($item['observacion']) && $item['observacion'] !== 'NINGUNA') {
                        $descripcion .= ' - ' . $item['observacion'];
                    }

                    return [
                        'description'    => $descripcion,
                        'quantity'       => $cantidad,
                        'unit_price'     => $precioVenta,
                        'tax_category_id'=> 5, // Excluido de IVA
                    ];
                })->toArray();

                $service->createFromImport([
                    'company_id'  => $companyId,
                    'customer_id' => $customer->id,
                    'number'      => $number,
                    'issue_date'  => $first['issue_date'],
                    'items'       => $invoiceItems,
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Factura {$number}: " . $e->getMessage();
            }
        }

        $msg = "{$imported} facturas importadas con contabilidad y cartera.";
        if ($skipped)       $msg .= " {$skipped} omitidas (ya existían).";

        if (count($errors)) {
            $msg .= " " . count($errors) . " errores.";
            return redirect()->route('invoices.index')
                ->with($imported > 0 ? 'success' : 'error', $msg)
                ->with('import_errors', $errors);
        }

        return redirect()->route('invoices.index')->with('success', $msg);
    }

    private function parseCsv(string $path): array
    {
        $rows = [];

        $content = file_get_contents($path);
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8,ISO-8859-1,Windows-1252');
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        $tmpPath = tempnam(sys_get_temp_dir(), 'inv_csv_');
        file_put_contents($tmpPath, $content);

        $handle = fopen($tmpPath, 'r');
        $header = null;

        while (($line = fgetcsv($handle, 2000, ',')) !== false) {
            $line = array_map(fn($v) => trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v)), $line);

            if (!$header) {
                $header = array_map('strtolower', $line);
                continue;
            }

            if (count($line) < 12) continue;

            // FECHA, NUMERO_FACTURA, NIT_CLIENTE, NOMBRE_CLIENTE, ZONA,
            // TIPO_PRODUCTO, LINEA, OBSERVACION, CANTIDAD,
            // PRECIO_COMPRA, PRECIO_VENTA, TOTAL_VENTA, FACTURADO
            $dateRaw      = trim($line[0] ?? '');
            $invoiceNum   = trim($line[1] ?? '');
            $nit          = trim($line[2] ?? '');
            $nombre       = trim($line[3] ?? '');
            $zona         = trim($line[4] ?? '');
            $tipo         = strtoupper(trim($line[5] ?? 'POLLITO'));
            $linea        = strtoupper(trim($line[6] ?? ''));
            $observacion  = strtoupper(trim($line[7] ?? ''));
            $cantidad     = (float) str_replace([',', ' '], '', $line[8] ?? '0');
            $precioCompra = (float) str_replace([',', ' ', '$'], '', $line[9] ?? '0');
            $precioVenta  = (float) str_replace([',', ' ', '$'], '', $line[10] ?? '0');
            $totalVenta   = (float) str_replace([',', ' ', '$'], '', $line[11] ?? '0');
            $facturado    = strtoupper(trim($line[12] ?? 'NO'));

            // Parsear fecha
            $date = null;
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateRaw)) {
                $date = $dateRaw;
            } elseif (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = (int)$m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $totalLinea = $totalVenta ?: ($cantidad * $precioVenta);

            $rows[] = [
                'issue_date'     => $date,
                'invoice_number' => $invoiceNum === 'SIN' ? null : $invoiceNum,
                'nit_cliente'    => $nit,
                'nombre_cliente' => $nombre,
                'zona'           => $zona,
                'tipo_producto'  => $tipo,
                'linea'          => $linea,
                'observacion'    => $observacion,
                'cantidad'       => $cantidad,
                'precio_compra'  => $precioCompra,
                'precio_venta'   => $precioVenta,
                'total_linea'    => $totalLinea,
                'total_venta'    => $totalVenta,
                'facturado'      => $facturado,
                'import'         => '1',
            ];
        }

        fclose($handle);
        @unlink($tmpPath);

        return $rows;
    }
}
