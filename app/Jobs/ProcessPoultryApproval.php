<?php
/*
|--------------------------------------------------------------------------
| TIZZILA APP - © 2026 Tizzila App · Orquestación Avícola Inteligente
|--------------------------------------------------------------------------
| Proyecto privado desarrollado por:
| Ingeniero Jhoan Romero Rivera
| LinkedIn: https://linkedin.com/in/jmromeror87
|--------------------------------------------------------------------------
*/


namespace App\Jobs;

use App\Models\Poultry\PoultryOrderApproval;
use App\Services\Poultry\VisionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessPoultryApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries   = 1; // ❗ NO reintentos automáticos

    public function __construct(
        public int $approvalId
    ) {}

    public function handle(VisionService $vision): void
    {
        $approval = PoultryOrderApproval::find($this->approvalId);

        if (!$approval) {
            Log::error('JOB: approval not found', ['approval_id' => $this->approvalId]);
            return;
        }

        try {
            Log::info('JOB: started', [
                'approval_id' => $approval->id,
                'path'        => $approval->source_document_path,
            ]);

            // 1️⃣ Resolver imagen
            if (
                empty($approval->source_document_path) ||
                !Storage::disk('public')->exists($approval->source_document_path)
            ) {
                throw new \Exception(
                    'Image file does not exist: ' . $approval->source_document_path
                );
            }

            $imagePath = Storage::disk('public')->path($approval->source_document_path);

            if (!is_file($imagePath)) {
                throw new \Exception('Resolved path is not a file: ' . $imagePath);
            }

            Log::info('JOB: image resolved', ['imagePath' => $imagePath]);

            // 2️⃣ IA
            $data = $vision->extractData($imagePath);

            // Guardar OCR crudo SIEMPRE (auditoría)
            $approval->update([
                'ocr_text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            ]);

            DB::transaction(function () use ($approval, $data) {

                // 3️⃣ Limpiar batches previos
                $approval->batches()->delete();

                // 4️⃣ Crear batches
                $totalApproved = 0;

                foreach ($data['batches'] ?? [] as $batch) {
                    $date = $batch['delivery_date'] ?? null;
                    $qty  = (int) ($batch['approved_quantity'] ?? 0);

                    if (!$date || $qty <= 0) {
                        continue;
                    }

                    $approval->batches()->create([
                        'delivery_date'     => $date,
                        'approved_quantity' => $qty,
                    ]);

                    $totalApproved += $qty;
                }

                // 5️⃣ Determinar poultry_type desde flags
                $flags = $data['product_flags'] ?? [];

                $poultryType =
                    (!empty($flags['bb']) && $flags['bb']) ? 'bb'
                    : ((!empty($flags['lsl']) && $flags['lsl']) ? 'lsl'
                    : ((!empty($flags['lohmann']) && $flags['lohmann']) ? 'lohmann'
                    : $approval->poultry_type));

                // 6️⃣ Mapear costos (ESTRUCTURA CORRECTA)
                $unitCost    = (float) data_get($data, 'pricing.unit_cost', 0);
                $fonavCost   = (float) data_get($data, 'pricing.fonav_cost', 0);
                $vaccineCost = (float) data_get($data, 'pricing.vaccine_cost', 0);

                // 7️⃣ Actualizar encabezado
                $approval->update([
                    // Documento
                    'provider_order_number' => $data['provider_order_number'] ?? null,
                    'document_date'         => $data['document_date'] ?? null,
                    'poultry_type'          => $poultryType,

                    // Vacunas
                    'vaccine_marek'   => (bool) data_get($data, 'vaccines.marek', false),
                    'vaccine_gumboro' => (bool) data_get($data, 'vaccines.gumboro', false),
                    'vaccine_others'  => data_get($data, 'vaccines.others'),

                    // Costos
                    'unit_cost'       => $unitCost,
                    'fonav_cost'      => $fonavCost,
                    'vaccine_cost'    => $vaccineCost,
                    'total_unit_cost' => $unitCost + $fonavCost + $vaccineCost,

                    // Totales
                    'approved_quantity' => $totalApproved,

                    // Estado
                    'approval_status' => 'approved',
                    'processed_at'    => now(),
                    'error_message'   => null,
                ]);
            });

            Log::info('JOB: approval processed OK', [
                'approval_id'      => $approval->id,
                'approved_quantity'=> $approval->approved_quantity,
                'batches'          => $approval->batches()->count(),
            ]);

        } catch (Throwable $e) {

            Log::error('JOB: FAILED', [
                'approval_id' => $approval->id,
                'error'       => $e->getMessage(),
            ]);

            $approval->update([
                'approval_status' => 'failed',
                'processed_at'    => now(),
                'error_message'   => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
