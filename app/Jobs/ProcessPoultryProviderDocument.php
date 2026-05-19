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

use App\Models\Poultry\PoultryProviderDocument;
use App\Models\Poultry\PoultryType;
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

class ProcessPoultryProviderDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries   = 1;

    public function __construct(
        public int $documentId
    ) {}

    public function handle(VisionService $vision): void
    {
        $document = PoultryProviderDocument::find($this->documentId);

        if (!$document) {
            Log::error('DOC JOB: document not found', [
                'document_id' => $this->documentId
            ]);
            return;
        }

        try {

            if (
                empty($document->file_path) ||
                !Storage::disk('public')->exists($document->file_path)
            ) {
                throw new \Exception('Document image not found');
            }

            $imagePath = Storage::disk('public')->path($document->file_path);

            $data = $vision->extractData($imagePath);

            DB::transaction(function () use ($document, $data) {

                $document->update([
                    'ocr_text'          => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                    'ia_payload'        => $data,
                    'processing_status' => 'processed',
                    'error_message'     => null,
                ]);

                // 🔁 Limpiar batches previos
                $document->batches()->delete();

                // 🔥 Resolver poultry_type_id usando CODE (no slug)
                $typeCode = null;

                if (data_get($data, 'product_flags.bb')) {
                    $typeCode = 'bb';
                } elseif (data_get($data, 'product_flags.lsl')) {
                    $typeCode = 'lsl';
                } elseif (data_get($data, 'product_flags.lohmann')) {
                    $typeCode = 'lohmann_brown';
                } elseif (data_get($data, 'product_flags.engorde')) {
                    $typeCode = 'engorde';
                } elseif (data_get($data, 'product_flags.pollita_levantada')) {
                    $typeCode = 'pollita_levantada';
                }

                $poultryTypeId = $typeCode
                    ? PoultryType::where('code', $typeCode)->value('id')
                    : null;

                if (!$poultryTypeId) {
                    throw new \Exception('No se pudo resolver poultry_type_id desde product_flags');
                }

                foreach ($data['batches'] ?? [] as $batch) {

                    if (
                        empty($batch['delivery_date']) ||
                        empty($batch['approved_quantity'])
                    ) {
                        continue;
                    }

                    $document->batches()->create([
                        'delivery_date'   => $batch['delivery_date'],
                        'quantity'        => (int) $batch['approved_quantity'],
                        'poultry_type_id' => $poultryTypeId,
                    ]);
                }

                // 📅 Inferir periodo automáticamente
                $dates = collect($data['batches'] ?? [])
                    ->pluck('delivery_date')
                    ->filter()
                    ->sort()
                    ->values();

                if ($dates->count() > 0) {
                    $document->update([
                        'period_start' => $dates->first(),
                        'period_end'   => $dates->last(),
                    ]);
                }
            });

        } catch (Throwable $e) {

            Log::error('DOC JOB FAILED', [
                'document_id' => $document->id ?? null,
                'error'       => $e->getMessage(),
            ]);

            $document?->update([
                'processing_status' => 'failed',
                'error_message'     => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
