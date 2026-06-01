<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Customer\Customer;

class InvoiceImportController extends Controller
{
    public function form()
    {
        return view('invoice.import');
    }

    public function template()
    {
        $rows = [
            ['NUMERO', 'FECHA', 'NIT_CLIENTE', 'DESCRIPCION', 'CANTIDAD', 'PRECIO_UNITARIO', 'TOTAL_LINEA', 'TOTAL_FACTURA'],
            ['006826', '2026-01-09', '88032951',    'POLLITO BB',   '1050', '11000', '11550000', '11550000'],
            ['006827', '2026-01-09', '77023217',    'POLLITO BB',   '600',  '11000', '6600000',  '6600000'],
            ['006828', '2026-01-09', '1090485643',  'POLLITA BB',   '600',  '11000', '6600000',  '6600000'],
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

        $path = $request->file('csv_file')->getRealPath();
        $rows = $this->parseCsv($path);

        // Agrupar por número de factura para mostrar resumen en preview
        $invoices = collect($rows)->groupBy('number')->map(function ($items) {
            return [
                'number'      => $items->first()['number'],
                'issue_date'  => $items->first()['issue_date'],
                'nit_cliente' => $items->first()['nit_cliente'],
                'total'       => $items->first()['total_factura'],
                'items'       => $items->toArray(),
                'import'      => '1',
            ];
        })->values()->toArray();

        return view('invoice.import-preview', compact('invoices', 'rows'));
    }

    public function import(Request $request)
    {
        $rows      = $request->input('rows', []);
        $companyId = Auth::user()->company_id ?? DB::table('companies')->value('id') ?? 1;

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        // Agrupar filas por número de factura
        $grouped = collect($rows)
            ->filter(fn($r) => !empty($r['import']) && $r['import'] == '1')
            ->groupBy('number');

        DB::transaction(function () use ($grouped, $companyId, &$imported, &$skipped, &$errors) {
            foreach ($grouped as $number => $items) {
                try {
                    $number = trim($number);

                    if (Invoice::where('number', $number)->exists()) {
                        $skipped++;
                        continue;
                    }

                    $first      = $items->first();
                    $totalFac   = (float) str_replace([',', ' ', '$'], '', $first['total_factura']);
                    $nit        = trim($first['nit_cliente']);
                    $customer   = Customer::where('identification_number', $nit)->first();

                    $invoice = Invoice::create([
                        'company_id'      => $companyId,
                        'customer_id'     => $customer?->id,
                        'number'          => $number,
                        'document_type'   => 'FVE',
                        'issue_datetime'  => $first['issue_date'] . ' 00:00:00',
                        'subtotal'        => $totalFac,
                        'taxable_amount'  => 0,
                        'exempt_amount'   => 0,
                        'excluded_amount' => $totalFac,
                        'tax_total'       => 0,
                        'total'           => $totalFac,
                        'balance'         => $totalFac,
                        'payment_status'  => 'pending',
                        'status'          => 'imported',
                        'environment'     => 'imported',
                    ]);

                    foreach ($items as $item) {
                        $cantidad   = (float) str_replace([',', ' '], '', $item['cantidad'] ?? 1);
                        $precioUnit = (float) str_replace([',', ' ', '$'], '', $item['precio_unitario'] ?? 0);
                        $totalLinea = (float) str_replace([',', ' ', '$'], '', $item['total_linea'] ?? ($cantidad * $precioUnit));

                        if ($cantidad <= 0) $cantidad = 1;
                        if ($precioUnit <= 0 && $totalLinea > 0) $precioUnit = $totalLinea / $cantidad;

                        InvoiceItem::create([
                            'invoice_id'      => $invoice->id,
                            'description'     => $item['description'],
                            'quantity'        => $cantidad,
                            'unit_price'      => $precioUnit,
                            'line_extension'  => $totalLinea,
                            'tax_category_id' => 5, // Excluido de IVA
                            'tax_amount'      => 0,
                            'total_line'      => $totalLinea,
                        ]);
                    }

                    $imported++;
                } catch (\Throwable $e) {
                    $errors[] = "Factura {$number}: " . $e->getMessage();
                }
            }
        });

        $msg = "{$imported} facturas importadas.";
        if ($skipped)       $msg .= " {$skipped} omitidas (duplicadas).";
        if (count($errors)) $msg .= " " . count($errors) . " errores.";

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

            if (count($line) < 7) continue;

            // Formato: NUMERO, FECHA, NIT_CLIENTE, DESCRIPCION, CANTIDAD, PRECIO_UNITARIO, TOTAL_LINEA, TOTAL_FACTURA
            $number        = trim($line[0] ?? '');
            $dateRaw       = trim($line[1] ?? '');
            $nitCliente    = trim($line[2] ?? '');
            $description   = trim($line[3] ?? '');
            $cantidad      = trim($line[4] ?? '1');
            $precioUnit    = trim($line[5] ?? '0');
            $totalLinea    = trim($line[6] ?? '0');
            $totalFactura  = trim($line[7] ?? $totalLinea);

            // Parsear fecha YYYY-MM-DD o M/D/YY
            $date = null;
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateRaw)) {
                $date = $dateRaw;
            } elseif (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = $m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $rows[] = [
                'number'          => $number,
                'issue_date'      => $date,
                'nit_cliente'     => $nitCliente,
                'description'     => $description,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precioUnit,
                'total_linea'     => $totalLinea,
                'total_factura'   => $totalFactura,
                'import'          => '1',
            ];
        }

        fclose($handle);
        @unlink($tmpPath);

        return $rows;
    }
}
