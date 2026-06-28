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
            ['FECHA', 'TERCERO', 'DETALLE', 'VALOR'],
            ['2/4/26',  'RESTAURANTE MONTANAS AZULES HI',         'ALIMENTACION',                   '38500'],
            ['2/4/26',  'PARQUEADERO RUITOQUE GARDEN',            'PARQUEADERO',                    '15600'],
            ['2/5/26',  'SERVICIO AUTOMOTRIZ SOBRERUEDAS',        'PARQUEADERO CENTRO',             '160000'],
            ['2/6/26',  'ROSMARY CAJAS',                          'MARCAR CAJAS EN GIRON',          '7800'],
            ['2/8/26',  'ALKOSTO',                                'MCAFEE ANTIVIRUS MICROSOFT',     '299900'],
            ['2/11/26', 'INVERSIA SAS',                           'PAN DE BONO',                    '19000'],
            ['2/13/26', 'JOSE LUIS CARVAJALINO',                  'REMESAS CUCUTA',                 '50000'],
            ['2/21/26', 'ARMASIL DISTRIBUCIONES SAS',             'SEGURIDAD',                      '153919'],
            ['2/26/26', 'ALCALDIA OCANA',                         'IMPUESTO INDUSTRIA Y COMERCIO',  '51989000'],
            ['2/27/26', 'JOSE LUIS CARVAJALINO',                  'CUENTA COBRO FLETE POLLITO',     '400000'],
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
        $content = file_get_contents($path);
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8,ISO-8859-1,Windows-1252');
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $tmpPath = tempnam(sys_get_temp_dir(), 'csv_');
        file_put_contents($tmpPath, $content);

        $handle = fopen($tmpPath, 'r');
        $header = null;

        while (($line = fgetcsv($handle, 1000, ',')) !== false) {
            $line = array_map(fn($v) => trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v)), $line);

            if (!$header) {
                $header = array_map('strtolower', $line);
                continue;
            }

            if (count($line) < 3) continue;

            $cols = count($line);

            // Formato nuevo (4 col): FECHA, TERCERO, DETALLE, VALOR
            // Formato viejo (5 col): PAGO, DOC, FECHA, DETALLE, VALOR
            // Formato viejo extendido (6 col): PAGO, DOC, FECHA, TERCERO, DETALLE, VALOR
            if ($cols <= 4) {
                $dateRaw     = trim($line[0] ?? '');
                $tercero     = trim($line[1] ?? '');
                $description = trim($line[2] ?? '') ?: $tercero;
                $valueRaw    = trim($line[3] ?? $line[2] ?? '');
                $payMethod   = 'TRANSFERENCIA';
                $docNumber   = null;
            } elseif ($cols >= 6) {
                $payMethod   = trim($line[0] ?? '');
                $docNumber   = trim($line[1] ?? '');
                $dateRaw     = trim($line[2] ?? '');
                $tercero     = trim($line[3] ?? '');
                $description = trim($line[4] ?? '') ?: $tercero;
                $valueRaw    = trim($line[5] ?? '');
            } else {
                $payMethod   = trim($line[0] ?? '');
                $docNumber   = trim($line[1] ?? '');
                $dateRaw     = trim($line[2] ?? '');
                $tercero     = '';
                $description = trim($line[3] ?? '');
                $valueRaw    = trim($line[4] ?? '');
            }

            // Parsear fecha: M/D/YY o D/M/YY o YYYY-MM-DD
            $date = null;
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $dateRaw)) {
                $date = $dateRaw;
            } elseif (preg_match('|(\d+)/(\d+)/(\d+)|', $dateRaw, $m)) {
                $year = (int)$m[3] < 100 ? 2000 + (int)$m[3] : (int)$m[3];
                $date = sprintf('%04d-%02d-%02d', $year, $m[1], $m[2]);
            }

            $total = (float) str_replace([',', ' ', '$', '"', '.'], '', $valueRaw);
            // Si el valor tiene punto decimal real (ej: 38500.00) re-parsear correctamente
            if (str_contains($valueRaw, '.') && !str_contains($valueRaw, ',')) {
                $total = (float) str_replace([',', ' ', '$', '"'], '', $valueRaw);
            }

            // Detectar categoría usando tercero + detalle juntos
            $catId = $this->detectCategory($tercero . ' ' . $description);

            $rows[] = [
                'payment_method'  => $payMethod,
                'document_number' => $docNumber,
                'expense_date'    => $date,
                'tercero'         => $tercero,
                'description'     => $description ?: $tercero,
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
