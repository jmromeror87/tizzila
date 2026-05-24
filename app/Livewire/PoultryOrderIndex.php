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


namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Poultry\PoultryOrderSchedule;
use App\Jobs\ProcessPoultryApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PoultryOrderIndex extends Component // ✅ Corregido: "extends"
{
    use WithFileUploads;

    public $search = '';
    public $month;
    public $year;

    // Propiedades del Modal
    public $showApprovalModal = false;
    public $selectedOrder = null;
    public $photo;

    public function mount($month = null, $year = null)
    {
        $this->month = $month ?? date('n');
        $this->year  = $year  ?? date('Y');
    }

    public function openApprovalModal($orderId)
    {
        $this->reset(['photo']);
        $this->resetErrorBag();

        $this->selectedOrder = PoultryOrderSchedule::with('provider')
            ->find($orderId);

        if ($this->selectedOrder) {
            $this->showApprovalModal = true;
        }
    }

    /**
     * 🚀 FLUJO FLASH DEFINITIVO
     * Se dispara automáticamente cuando el usuario selecciona una imagen.
     */
    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:10240', // 10MB Máx
        ]);

        if (!$this->selectedOrder) {
            session()->flash('error', 'Pedido no válido.');
            return;
        }

        try {
            DB::transaction(function () {
                // 1️⃣ Guardar imagen en DISCO PUBLIC
                $finalPath = $this->photo->store('approvals', 'public');

                if (!Storage::disk('public')->exists($finalPath)) {
                    throw new \Exception('La imagen no se pudo guardar en el servidor.');
                }

                // 2️⃣ Crear aprobación en estado PROCESSING
                $approval = $this->selectedOrder->approval()->create([
                    'approval_status'      => 'processing',
                    'approved_quantity'    => 0,
                    'source_document_path' => $finalPath,
                    'approved_by'          => Auth::id(),
                ]);

                // 3️⃣ Registrar documento (metadata para la galería)
                $this->selectedOrder->documents()->create([
                    'file_path'                 => $finalPath,
                    'original_name'             => $this->photo->getClientOriginalName(),
                    'mime_type'                 => $this->photo->getMimeType(),
                    'document_type'             => 'provider_confirmation',
                    'poultry_order_approval_id' => $approval->id,
                ]);

                // 4️⃣ Despachar Job para procesamiento con IA
                ProcessPoultryApproval::dispatch($approval->id);
            });

            // 5️⃣ UX y Limpieza
            $this->reset(['photo', 'showApprovalModal']);
            session()->flash('success', 'Documento recibido. Procesando con IA…');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteOrder($id)
    {
        if ($order = PoultryOrderSchedule::find($id)) {
            $order->delete();
            session()->flash('success', 'Pedido eliminado.');
        }
    }

    public function render()
    {
        $query = PoultryOrderSchedule::with([
                'provider',
                'documents',
                'approval.batches',
                'purchaseInvoice',
                'distributions',
            ])
            ->whereMonth('dispatch_date', $this->month)
            ->whereYear('dispatch_date', $this->year);

        // Filtro de búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('provider', fn ($s) =>
                    $s->where('business_name', 'like', "%{$this->search}%")
                )->orWhere('id', 'like', "%{$this->search}%");
            });
        }

        return view('livewire.poultry-order-index', [
            'orders' => $query
                ->orderBy('dispatch_date')
                ->get()
        ]);
    }
}