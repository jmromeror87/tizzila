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
            ['NUMERO', 'FECHA', 'NIT_CLIENTE', 'DESCRIPCION', 'VALOR'],
            ['006826', '2026-01-09', '88032951', 'POLLITO BB', '11550000'],
            ['006827', '2026-01-09', '77023217', 'POLLITO BB', '6600000'],
            ['006828', '2026-01-16', '1090485643', 'POLLITAS BB CON SERVICIO DESPIQUE', '2485000'],
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

        return view('invoice.import-preview', compact('rows'));
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
                    $total = (float) str_replace([',', ' ', '$'], '', $row['total']);
                    if ($total <= 0) { $skipped++; continue; }

                    $number = trim($row['number']);

                    // Evitar duplicados por número
                    if (Invoice::where('number', $number)->exists()) {
                        $skipped++;
                        continue;
                    }

                    // Buscar cliente por NIT
                    $nit      = trim($row['nit_cliente']);
                    $customer = Customer::where('identification_number', $nit)->first();

                    $invoice = Invoice::create([
                        'company_id'     => $companyId,
                        'customer_id'    => $customer?->id,
                        'number'         => $number,
                        'document_type'  => 'FVE',
                        'issue_datetime' => $row['issue_date'] . ' 00:00:00',
                        'subtotal'       => $total,
                        'taxable_amount' => 0,
                        'exempt_amount'  => 0,
                        'excluded_amount'=> $total,
                        'tax_total'      => 0,
                        'total'          => $total,
                        'balance'        => $total,
                        'payment_status' => 'pending',
                        'status'         => 'imported',
                        'environment'    => 'imported',
                    ]);

                    InvoiceItem::create([
                        'invoice_id'      => $invoice->id,
                        'description'     => $row['description'],
                        'quantity'        => 1,
                        'unit_price'      => $total,
                        'line_extension'  => $total,
                        'tax_category_id' => 5, // Excluido de IVA (pollito BB)
                        'tax_amount'      => 0,
                        'total_line'      => $total,
                    ]);

                    $imported++;
                } catch (\Throwable $e) {
                    $errors[] = "Fila " . ($i + 1) . ": " . $e->getMessage();
                }
            }
        });

        $msg = "{$imported} facturas importadas.";
        if ($skipped)        $msg .= " {$skipped} omitidas (duplicadas o desmarcadas).";
        if (count($errors))  $msg .= " " . count($errors) . " errores.";

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

            if (count($line) < 5) continue;

            $number      = trim($line[0] ?? '');
            $dateRaw     = trim($line[1] ?? '');
            $nitCliente  = trim($line[2] ?? '');
            $description = trim($line[3] ?? '');
            $valueRaw    = trim($line[4] ?? '');

            // Parsear fecha: acepta YYYY-MM-DD, M/D/YY o M/D/YYYY
            $date = null;
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateRaw, $m)) {
                $date = $dateRaw;
            } elseif (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = $m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $total = (float) str_replace([',', ' ', '$', '"'], '', $valueRaw);

            $rows[] = [
                'number'      => $number,
                'issue_date'  => $date,
                'nit_cliente' => $nitCliente,
                'description' => $description,
                'total'       => $total,
                'import'      => '1',
            ];
        }

        fclose($handle);
        @unlink($tmpPath);

        return $rows;
    }
}
