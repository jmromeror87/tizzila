<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Expenses\Expense;
use App\Models\Expenses\ExpenseCategory;

class ExpenseImportController extends Controller
{
    // Palabras clave → category_id
    private array $categoryMap = [
        10 => ['peaje','combustible','gasolina','gas','flete','parqueadero','parking','transporte','vitara','morrison','picacho','pamplona','acacios','platanal','lebrija','gomez','autogas','eds'],
        11 => ['restaurante','comida','almuerzo','cena','desayuno','panaderia','bakery','coffee','brioche','platano','cinnamon','gourmet','pan de bono','inversia','matera','cuesta','nota colombia','villa barbacoa','provincia','mauren'],
        6  => ['honorarios','contador','contadora','revisor fiscal','zambrano','carvajalino','victoria perez','monica alvi'],
        26 => ['flete pollito','cuenta cobro victoria','cuenta cobro monica'],
        1  => ['parqueadero del pollito','parqueadero mensual','servicio parqueadero','sociedad de parqueos','unisan','mensuli','arrendamiento'],
        15 => ['seguridad social','eps','arl','pension','parafiscal'],
        8  => ['papeleria','papelería','utiles','útiles','nuevo punto'],
        12 => ['regalos','oboti','angel','marketing','publicidad'],
        7  => ['camara de comercio','certificado','servicios profesionales'],
        4  => ['seguridad','vigilancia','armasil'],
        19 => ['impuesto','tasa','reteiva','retencion'],
        22 => ['pollito','pollos','aves','costo pollito'],
        13 => ['salario','nomina','nómina'],
        14 => ['prestacion','prima','cesantia','vacacion'],
        9  => ['software','licencia','sistema','tizzila'],
        3  => ['mantenimiento','reparacion','reparación','taller'],
        17 => ['comision bancaria','bancolombia','davivienda','nequi comision'],
        21 => ['multa','sancion','sanción'],
        5  => ['aseo','limpieza','cafeteria'],
        2  => ['energia','agua','luz','gas domiciliario','internet','telefono','telefóno'],
        25 => ['mano obra','jornalero','granja'],
        20 => ['multa','infraccion','infracción'],
    ];

    private function detectCategory(string $detalle): int
    {
        $lower = strtolower($detalle);
        foreach ($this->categoryMap as $catId => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($lower, $kw)) {
                    return $catId;
                }
            }
        }
        return 21; // Gastos no deducibles como fallback
    }

    public function form()
    {
        return view('expenses.import');
    }

    public function template()
    {
        $rows = [
            ['MEDIO DE PAGO', 'DOC-SOPORTE', 'FECHA', 'DETALLE', 'VALOR'],
            ['EFECTIVO',      'GT-001',      '1/6/26', 'PEAJE MORRISON',                        '14100'],
            ['TRANSFERENCIA', 'GT-002',      '1/8/26', 'SERVICIO PARQUEADERO SOBRERUEDAS',      '160000'],
            ['EFECTIVO',      'GT-003',      '1/8/26', 'RESTAURANTE EL CARBON',                 '49000'],
            ['TRANSFERENCIA', 'GT-004',      '1/10/26','COMBUSTIBLE EDS AUTOGAS',               '101292'],
            ['TRANSFERENCIA', 'GT-005',      '1/15/26','HONORARIOS CONTADOR',                   '550000'],
            ['EFECTIVO',      'GT-006',      '1/20/26','PEAJE PAMPLONA',                        '20700'],
            ['TRANSFERENCIA', 'GT-007',      '1/31/26','SEGURIDAD SOCIAL LINA CABRALES',        '855000'],
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
            'Content-Disposition' => 'attachment; filename="plantilla_gastos_tizzila.csv"',
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $rows = $this->parseCsv($path);
        $categories = ExpenseCategory::orderBy('name')->pluck('name', 'id');

        return view('expenses.import-preview', compact('rows', 'categories'));
    }

    public function import(Request $request)
    {
        $rows = $request->input('rows', []);
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

                    $pm        = strtolower(trim($row['payment_method'] ?? ''));
                    $payMethod = str_contains($pm, 'trans') ? 'transfer' : 'cash';
                    $descFinal = !empty($row['tercero']) ? $row['tercero'] : $row['description'];

                    // Si ya existe → actualizar tercero, medio de pago y categoría
                    if (!empty($row['document_number'])) {
                        $existing = Expense::where('document_number', $row['document_number'])->first();
                        if ($existing) {
                            $existing->update([
                                'description'    => $descFinal,
                                'payment_method' => $payMethod,
                                'category_id'    => $row['category_id'],
                            ]);
                            $imported++;
                            continue;
                        }
                    }

                    Expense::create([
                        'company_id'      => $companyId,
                        'category_id'     => $row['category_id'],
                        'document_type'   => 'support_doc',
                        'document_number' => $row['document_number'] ?? null,
                        'tax_base'        => $total,
                        'iva'             => 0,
                        'retefuente'      => 0,
                        'total'           => $total,
                        'expense_date'    => $row['expense_date'],
                        'payment_method'  => $payMethod,
                        'description'     => $descFinal,
                        'created_by'      => Auth::id(),
                        'status'          => 'approved',
                    ]);

                    $imported++;
                } catch (\Throwable $e) {
                    $errors[] = "Fila " . ($i + 1) . ": " . $e->getMessage();
                }
            }
        });

        $msg = "{$imported} gastos importados.";
        if ($skipped)       $msg .= " {$skipped} omitidos (duplicados o desmarcados).";
        if (count($errors))  $msg .= " " . count($errors) . " errores.";

        return redirect()->route('expenses.index')->with('success', $msg);
    }

    private function parseCsv(string $path): array
    {
        $rows = [];
        // Convertir a UTF-8 limpio si viene en otro encoding
        $content = file_get_contents($path);
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8,ISO-8859-1,Windows-1252');
        // Quitar BOM
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $tmpPath = tempnam(sys_get_temp_dir(), 'csv_');
        file_put_contents($tmpPath, $content);

        $handle = fopen($tmpPath, 'r');
        $header = null;

        while (($line = fgetcsv($handle, 1000, ',')) !== false) {
            // Limpiar caracteres de control pero NO los acentos
            $line = array_map(fn($v) => trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v)), $line);

            if (!$header) {
                $header = array_map('strtolower', $line);
                continue;
            }

            if (count($line) < 4) continue;

            // Formato 5 col: PAGO, DOC, FECHA, DETALLE, VALOR
            // Formato 6 col: PAGO, DOC, FECHA, TERCERO, DETALLE, VALOR
            $cols = count($line);
            $payMethod   = trim($line[0] ?? '');
            $docNumber   = trim($line[1] ?? '');
            $dateRaw     = trim($line[2] ?? '');
            if ($cols >= 6) {
                $tercero     = trim($line[3] ?? '');
                $description = trim($line[4] ?? '') ?: $tercero;
                $valueRaw    = trim($line[5] ?? '');
            } else {
                $tercero     = '';
                $description = trim($line[3] ?? '');
                $valueRaw    = trim($line[4] ?? '');
            }

            // Parsear fecha M/D/YY → Y-m-d
            $date = null;
            if (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = $m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $total = (float) str_replace([',', ' ', '$', '"'], '', $valueRaw);

            $catId = $this->detectCategory($description);

            $rows[] = [
                'payment_method'  => $payMethod,
                'document_number' => $docNumber,
                'expense_date'    => $date,
                'tercero'         => $tercero,
                'description'     => $description,
                'total'           => $total,
                'category_id'     => $catId,
                'import'          => '1',
            ];
        }

        fclose($handle);
        @unlink($tmpPath);
        return $rows;
    }
}
