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


namespace App\Models\Dispatch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DispatchEvidence extends Model
{
    protected $table = 'dispatch_evidences';

    protected $fillable = [
        'dispatch_confirmation_id',
        'image_path',
        'mime_type',
        'size_int',
    ];

    protected $casts = [
        'size_int' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function confirmation(): BelongsTo
    {
        return $this->belongsTo(
            DispatchConfirmation::class,
            'dispatch_confirmation_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getUrlAttribute(): ?string
    {
        return $this->image_path
            ? Storage::url($this->image_path)
            : null;
    }
}
