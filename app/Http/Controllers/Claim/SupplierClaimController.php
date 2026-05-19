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


namespace App\Http\Controllers\Claim;

use App\Http\Controllers\Controller;
use App\Models\Claim\SupplierClaim;
use App\Models\Dispatch\DispatchConfirmation;
use App\Models\Dispatch\PoultryDispatchRouteStop;
use App\Models\Poultry\PoultryProviderDocument;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;



class SupplierClaimController extends Controller
{
    /**
     * Listado de reclamos
     */


public function index()
{
    $claims = SupplierClaim::with([
        'provider',
        'confirmation',
        'evidences'
    ])->latest()->get();

    $deadConfirmations = DispatchConfirmation::where('dead_quantity', '>', 0)
        ->doesntHave('supplierClaim')
        ->with('evidences')
        ->latest()
        ->get();

    return view('claims.index', [
        'claims' => $claims,
        'deadConfirmations' => $deadConfirmations
    ]);
}

    /**
     * Ver detalle del reclamo
     */
    public function show($id)
    {
        $claim = SupplierClaim::with([
            'provider',
            'confirmation',
            'evidences'
        ])->findOrFail($id);

        return view('claims.show', compact('claim'));
    }

    /**
     * Enviar reclamo al proveedor
     */
    public function submit($id)
    {
        $claim = SupplierClaim::findOrFail($id);

        $claim->update([
            'status' => SupplierClaim::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Claim submitted to provider.');
    }

    /**
     * Aprobar reclamo
     */
    public function approve($id)
    {
        $claim = SupplierClaim::findOrFail($id);

        $claim->update([
            'status' => SupplierClaim::STATUS_APPROVED,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Claim approved.');
    }

    public function downloadPdf($id)
{
    $claim = SupplierClaim::with([
        'provider',
        'confirmation'
    ])->findOrFail($id);

    $pdf = Pdf::loadView('claims.pdf', compact('claim'));

    return $pdf->download(
        'supplier-claim-'.$claim->id.'.pdf'
    );
}

    /**
     * Rechazar reclamo
     */
    public function reject($id)
    {
        $claim = SupplierClaim::findOrFail($id);

        $claim->update([
            'status' => SupplierClaim::STATUS_REJECTED,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Claim rejected.');
    }

    public function generate($confirmationId)
{
    $confirmation = DispatchConfirmation::findOrFail($confirmationId);

    /*
    |--------------------------------------------------------------------------
    | Validar muertos
    |--------------------------------------------------------------------------
    */

    if ($confirmation->dead_quantity <= 0) {
        return back()->with('error','No hay pollitos muertos.');
    }

    DB::transaction(function () use ($confirmation) {

        /*
        |--------------------------------------------------------------------------
        | Bloquear fila para evitar reclamos duplicados concurrentes
        |--------------------------------------------------------------------------
        */
        $existing = SupplierClaim::where(
            'dispatch_confirmation_id',
            $confirmation->id
        )->lockForUpdate()->first();

        if ($existing) {
            return; // se maneja fuera con el redirect de warning
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener provider desde la parada → programación real
        |--------------------------------------------------------------------------
        */
        $stop = PoultryDispatchRouteStop::with('orderSchedule')
            ->where('id', $confirmation->dispatch_stop_id)
            ->first();

        if (!$stop || !$stop->orderSchedule) {
            throw new \Exception('No se encontró la programación asociada a esta entrega.');
        }

        $providerId = $stop->orderSchedule->provider_id;

        /*
        |--------------------------------------------------------------------------
        | Obtener precio del documento del proveedor vinculado a la programación
        |--------------------------------------------------------------------------
        */
        $providerDocument = PoultryProviderDocument::where('provider_id', $providerId)
            ->latest()
            ->first();

        $unitPrice = 0;

        if ($providerDocument && $providerDocument->ia_payload) {
            $unitPrice = $providerDocument->ia_payload['pricing']['unit_cost'] ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear claim
        |--------------------------------------------------------------------------
        */
        SupplierClaim::create([
            'dispatch_confirmation_id' => $confirmation->id,
            'provider_id'              => $providerId,
            'scheduled_quantity'       => $confirmation->scheduled_quantity,
            'received_quantity'        => $confirmation->received_quantity,
            'dead_quantity'            => $confirmation->dead_quantity,
            'unit_price'               => $unitPrice,
            'claim_amount'             => $confirmation->dead_quantity * $unitPrice,
            'status'                   => SupplierClaim::STATUS_PENDING,
        ]);
    });

    // Verificar si ya existía (fue creado por otra request concurrente)
    $alreadyExists = SupplierClaim::where('dispatch_confirmation_id', $confirmation->id)->exists();

    if ($alreadyExists && !SupplierClaim::where('dispatch_confirmation_id', $confirmation->id)
        ->where('created_at', '>=', now()->subSeconds(3))->exists()) {
        return back()->with('warning', 'Este reclamo ya fue generado.');
    }

    return redirect()
        ->route('claims.index')
        ->with('success', 'Reclamo generado correctamente.');
}

    /**
     * Marcar como crédito
     */
    public function credited($id)
    {
        $claim = SupplierClaim::findOrFail($id);

        $claim->update([
            'status' => SupplierClaim::STATUS_CREDITED,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Claim credited.');
    }
}