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

                    $pm = strtolower(trim($row['payment_method'] ?? ''));
                    $payMethod = (str_contains($pm, 'trans') || str_contains($pm, 'transf')) ? 'transfer' : 'cash';

                    // Evitar duplicados por document_number
                    if (!empty($row['document_number']) &&
                        Expense::where('document_number', $row['document_number'])->exists()) {
                        $skipped++;
                        continue;
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
                        'description'     => $row['description'],
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
        $handle = fopen($path, 'r');
        $header = null;

        while (($line = fgetcsv($handle, 1000, ',')) !== false) {
            // Limpiar BOM y espacios
            $line = array_map(fn($v) => trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $v)), $line);

            if (!$header) {
                $header = array_map('strtolower', $line);
                continue;
            }

            if (count($line) < 4) continue;

            // Columnas: MEDIO DE PAGO, DOC-SOPORTE, FECHA, DETALLE, VALOR
            $payMethod   = trim($line[0] ?? '');
            $docNumber   = trim($line[1] ?? '');
            $dateRaw     = trim($line[2] ?? '');
            $description = trim($line[3] ?? '');
            $valueRaw    = trim($line[4] ?? '');

            // Parsear fecha M/D/YY → Y-m-d
            $date = null;
            if (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = $m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $total = (float) str_replace([',', ' ', '$', '"'], '', $valueRaw);

            $catId = $this->detectCategory($description);

            $rows[] = [
                'payment_method' => $payMethod,
                'document_number'=> $docNumber,
                'expense_date'   => $date,
                'description'    => $description,
                'total'          => $total,
                'category_id'    => $catId,
                'import'         => '1',
            ];
        }

        fclose($handle);
        return $rows;
    }
}
