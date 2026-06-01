<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sales\SaleRecord;
use App\Models\Customer\Customer;

class SaleImportController extends Controller
{
    public function form()
    {
        return view('sales.import');
    }

    public function template()
    {
        $rows = [
            ['FECHA', 'NUMERO_FACTURA', 'NIT_CLIENTE', 'NOMBRE_CLIENTE', 'ZONA', 'TIPO_PRODUCTO', 'LINEA', 'OBSERVACION', 'CANTIDAD', 'PRECIO_COMPRA', 'PRECIO_VENTA', 'TOTAL_VENTA', 'FACTURADO'],
            ['2026-01-09', 'FVE6826', '88032951',   'JUAN CARLOS CAMARGO JEREZ',          'PAMPLONA',   'POLLITO', 'BROILER', 'NINGUNA',  '3500', '2620', '3300', '11550000', 'SI'],
            ['2026-01-09', 'FVE6827', '77023217',   'NELSON CASTRO RUEDAS',               'PAMPLONITA', 'POLLITO', 'BROILER', 'NINGUNA',  '2000', '2620', '3300',  '6600000', 'SI'],
            ['2026-01-16', 'SIN',     '901243904',  'AGROQUIMICOS SAS',                   'CUCUTA',     'POLLITO', 'BROILER', 'NINGUNA',  '7000', '2620', '3300', '23100000', 'NO'],
            ['2026-01-16', 'FVE6840', '901885356',  'DISTRIBUIDORA AGROPECUARIA GOMEZ',   'CUCUTA',     'POLLITA', 'LOHMAN',  'DESPIQUE',  '500', '4490', '4970',  '2485000', 'SI'],
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
            'Content-Disposition' => 'attachment; filename="plantilla_ventas_tizzila.csv"',
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $rows = $this->parseCsv($request->file('csv_file')->getRealPath());

        $totalVenta   = collect($rows)->sum('total_venta');
        $totalCompra  = collect($rows)->sum('total_compra');
        $totalUtilidad= collect($rows)->sum('utilidad');
        $facturadas   = collect($rows)->where('facturado', 'SI')->count();
        $sinFactura   = collect($rows)->where('facturado', 'NO')->count();

        return view('sales.import-preview', compact(
            'rows', 'totalVenta', 'totalCompra', 'totalUtilidad', 'facturadas', 'sinFactura'
        ));
    }

    public function import(Request $request)
    {
        $rows      = $request->input('rows', []);
        $companyId = Auth::user()->company_id ?? DB::table('companies')->value('id') ?? 1;

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        DB::transaction(function () use ($rows, $companyId, &$imported, &$skipped, &$errors) {
            foreach ($rows as $i => $row) {
                if (empty($row['import']) || $row['import'] != '1') {
                    $skipped++;
                    continue;
                }

                try {
                    $cantidad    = (float) str_replace([',', ' '], '', $row['cantidad'] ?? 0);
                    $precioCompra= (float) str_replace([',', ' ', '$'], '', $row['precio_compra'] ?? 0);
                    $precioVenta = (float) str_replace([',', ' ', '$'], '', $row['precio_venta'] ?? 0);
                    $totalVenta  = (float) str_replace([',', ' ', '$'], '', $row['total_venta'] ?? 0);
                    $totalCompra = $cantidad * $precioCompra;
                    $utilidad    = $totalVenta - $totalCompra;

                    $facturado     = strtoupper(trim($row['facturado'] ?? 'NO')) === 'SI';
                    $invoiceStatus = $facturado ? 'facturado' : 'sin_factura';
                    $invoiceNumber = trim($row['invoice_number'] ?? '');
                    if ($invoiceNumber === 'SIN' || $invoiceNumber === '') $invoiceNumber = null;

                    $nit      = trim($row['nit_cliente'] ?? '');
                    $customer = $nit ? Customer::where('identification_number', $nit)->first() : null;

                    $tipoProducto = strtolower(trim($row['tipo_producto'] ?? 'pollito'));
                    if (!in_array($tipoProducto, ['pollito', 'pollita'])) $tipoProducto = 'pollito';

                    SaleRecord::create([
                        'company_id'     => $companyId,
                        'invoice_number' => $invoiceNumber,
                        'invoice_status' => $invoiceStatus,
                        'sale_date'      => $row['sale_date'],
                        'nit_cliente'    => $nit ?: null,
                        'nombre_cliente' => trim($row['nombre_cliente'] ?? ''),
                        'customer_id'    => $customer?->id,
                        'zona'           => trim($row['zona'] ?? ''),
                        'tipo_producto'  => $tipoProducto,
                        'linea'          => strtoupper(trim($row['linea'] ?? '')),
                        'observacion'    => trim($row['observacion'] ?? ''),
                        'cantidad'       => $cantidad,
                        'precio_compra'  => $precioCompra,
                        'precio_venta'   => $precioVenta,
                        'total_compra'   => $totalCompra,
                        'total_venta'    => $totalVenta,
                        'utilidad'       => $utilidad,
                        'saldo'          => $totalVenta,
                        'payment_status' => 'pending',
                        'created_by'     => Auth::id(),
                    ]);

                    $imported++;
                } catch (\Throwable $e) {
                    $errors[] = "Fila " . ($i + 1) . ": " . $e->getMessage();
                }
            }
        });

        $msg = "{$imported} ventas importadas.";
        if ($skipped)       $msg .= " {$skipped} omitidas.";
        if (count($errors)) $msg .= " " . count($errors) . " errores.";

        return redirect()->route('sales.index')->with('success', $msg);
    }

    public function index(Request $request)
    {
        $query = SaleRecord::query();

        if ($request->filled('mes')) {
            $query->whereMonth('sale_date', $request->mes);
        }
        if ($request->filled('ano')) {
            $query->whereYear('sale_date', $request->ano);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo_producto', $request->tipo);
        }
        if ($request->filled('status')) {
            $query->where('invoice_status', $request->status);
        }

        $records = $query->orderBy('sale_date')->orderBy('invoice_number')->paginate(50);

        // Resumen
        $resumen = $query->selectRaw('
            SUM(total_venta) as total_venta,
            SUM(total_compra) as total_compra,
            SUM(utilidad) as total_utilidad,
            SUM(cantidad) as total_aves,
            COUNT(*) as total_registros
        ')->first();

        return view('sales.index', compact('records', 'resumen', 'request'));
    }

    private function parseCsv(string $path): array
    {
        $rows = [];

        $content = file_get_contents($path);
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8,ISO-8859-1,Windows-1252');
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        $tmpPath = tempnam(sys_get_temp_dir(), 'sale_csv_');
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
            $dateRaw     = trim($line[0] ?? '');
            $invoiceNum  = trim($line[1] ?? '');
            $nit         = trim($line[2] ?? '');
            $nombre      = trim($line[3] ?? '');
            $zona        = trim($line[4] ?? '');
            $tipo        = strtolower(trim($line[5] ?? 'pollito'));
            $linea       = strtoupper(trim($line[6] ?? ''));
            $observacion = trim($line[7] ?? '');
            $cantidad    = (float) str_replace([',', ' '], '', $line[8] ?? '0');
            $precioCompra= (float) str_replace([',', ' ', '$'], '', $line[9] ?? '0');
            $precioVenta = (float) str_replace([',', ' ', '$'], '', $line[10] ?? '0');
            $totalVenta  = (float) str_replace([',', ' ', '$'], '', $line[11] ?? '0');
            $facturado   = strtoupper(trim($line[12] ?? 'NO'));

            // Parsear fecha YYYY-MM-DD o M/D/YY
            $date = null;
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateRaw)) {
                $date = $dateRaw;
            } elseif (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = (int)$m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $totalCompra = $cantidad * $precioCompra;
            $utilidad    = $totalVenta - $totalCompra;

            $rows[] = [
                'sale_date'      => $date,
                'invoice_number' => $invoiceNum,
                'nit_cliente'    => $nit,
                'nombre_cliente' => $nombre,
                'zona'           => $zona,
                'tipo_producto'  => $tipo,
                'linea'          => $linea,
                'observacion'    => $observacion,
                'cantidad'       => $cantidad,
                'precio_compra'  => $precioCompra,
                'precio_venta'   => $precioVenta,
                'total_compra'   => $totalCompra,
                'total_venta'    => $totalVenta,
                'utilidad'       => $utilidad,
                'facturado'      => $facturado,
                'import'         => '1',
            ];
        }

        fclose($handle);
        @unlink($tmpPath);

        return $rows;
    }
}
