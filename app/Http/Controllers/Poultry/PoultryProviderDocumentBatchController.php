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


namespace App\Http\Controllers\Poultry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Poultry\PoultryProviderDocumentBatch;


class PoultryProviderDocumentBatchController extends Controller
{
    /**
     * Actualiza manualmente un lote detectado por IA
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'verified_quantity' => 'required|integer|min:0',
        'reason' => 'required|string|max:1000',
    ]);

    DB::transaction(function () use ($id, $request) {

        $batch = PoultryProviderDocumentBatch::where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

        // Snapshot original solo la primera vez
        if (is_null($batch->original_quantity)) {
            $batch->original_quantity = $batch->quantity;
        }

        $batch->verified_quantity = $request->verified_quantity;
        $batch->was_manually_edited = true;
        $batch->edited_by = Auth::id();
        $batch->edited_at = now();
        $batch->edit_reason = $request->reason;
        $batch->verification_status = 'human_verified';

        $batch->save();
    });

    return back()->with('success', 'Lote actualizado con trazabilidad completa.');
}
}