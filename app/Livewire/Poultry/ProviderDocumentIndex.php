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


namespace App\Livewire\Poultry;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination; // 1. IMPORTANTE: Para paginación en vivo
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Poultry\PoultryProviderDocument;
use App\Models\Poultry\Provider;
use App\Jobs\ProcessPoultryProviderDocument;
use Carbon\Carbon;

class ProviderDocumentIndex extends Component
{
    use WithFileUploads;
    use WithPagination; // 2. IMPORTANTE: Usar el trait

    public $month;
    public $year;
    public $provider_id;
    public $file;
    public $search = ''; // 3. Para la búsqueda en vivo

    public $showUploadModal = false;

    // Resetear página cuando se busca (para evitar que la búsqueda quede en una página vacía)
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->month = request('month', now()->month);
        $this->year  = request('year', now()->year);
    }

    /* --- NAVEGACIÓN --- */

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year  = $date->year;
        $this->resetPage();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year  = $date->year;
        $this->resetPage();
    }

    /* --- MODAL --- */

    public function openUploadModal()
    {
        $this->reset(['file', 'provider_id']);
        $this->showUploadModal = true;
    }

    /* --- CARGAR --- */

    public function cargar()
    {
        $this->validate([
            'provider_id' => 'required|exists:providers,id',
            'file'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:20480',
        ]);

        DB::transaction(function () {
            $path = $this->file->store('provider-documents', 'public');

            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('No se pudo guardar el documento.');
            }

            $document = PoultryProviderDocument::create([
                'provider_id'       => $this->provider_id,
                'file_path'         => $path,
                'processing_status' => 'pending',
            ]);

            ProcessPoultryProviderDocument::dispatch($document->id);
        });

        $this->reset(['file', 'provider_id', 'showUploadModal']);
        session()->flash('success', 'Documento recibido. Procesando con IA…');
    }

    /* --- RENDER --- */

    public function render()
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end   = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        $documents = PoultryProviderDocument::with('provider')
            ->where(function ($query) use ($start, $end) {
                if (!empty($this->search)) {
                    $query->whereHas('provider', function ($q) {
                        $q->where('business_name', 'like', '%' . $this->search . '%');
                    });
                }

                $query->where(function ($q) use ($start, $end) {
                    $q->whereNull('period_start')
                      ->orWhereBetween('period_start', [$start, $end])
                      ->orWhere(function ($q2) use ($start, $end) {
                          $q2->where('period_start', '<=', $end)
                             ->where('period_end', '>=', $start);
                      });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('livewire.poultry.provider-document-index', [
            'documents' => $documents,
            'providers' => Provider::orderBy('business_name')->get(),
        ]);
    }
}