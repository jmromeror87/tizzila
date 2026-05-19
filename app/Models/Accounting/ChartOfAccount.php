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


namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChartOfAccount extends Model
{
    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'company_id',
        'parent_id',
        'code',
        'name',
        'type',
        'level',
        'normal_balance',
        'is_posting',
        'requires_third_party',
        'requires_cost_center',
        'is_active',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'parent_id' => 'integer',
        'level' => 'integer',
        'is_posting' => 'boolean',
        'requires_third_party' => 'boolean',
        'requires_cost_center' => 'boolean',
        'is_active' => 'boolean',
    ];

    // RELACIONES

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(\App\Models\Accounting\JournalEntryLine::class, 'account_id');
    }
}