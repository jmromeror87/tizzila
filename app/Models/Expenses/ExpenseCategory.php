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


namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Expenses\Expense;
use App\Models\Expenses\RecurringExpense;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expense_categories';

    // ✅ Constantes de tipos — evita strings hardcodeados en todo el proyecto
    const TYPE_COST           = 'cost';
    const TYPE_OPERATIONAL    = 'operational';
    const TYPE_ADMINISTRATIVE = 'administrative';
    const TYPE_FINANCIAL      = 'financial';
    const TYPE_OTHER          = 'other';

    protected $fillable = [
        'name',
        'puc_code',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function recurringExpenses()
    {
        return $this->hasMany(RecurringExpense::class, 'expense_category_id');
    }

    // Cuenta contable del PUC asociada a esta categoría
    public function chartAccount()
    {
        return $this->belongsTo(
            \App\Models\Accounting\ChartOfAccount::class,
            'puc_code',
            'code'
        )->withDefault();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // ¿tiene cuenta PUC configurada?
    public function getHasPucAttribute(): bool
    {
        return !is_null($this->puc_code) && $this->puc_code !== '';
    }

    // ✅ Label legible para vistas y reportes
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_COST           => 'Costo de Producción',
            self::TYPE_OPERATIONAL    => 'Gasto Operacional',
            self::TYPE_ADMINISTRATIVE => 'Gasto Administrativo',
            self::TYPE_FINANCIAL      => 'Gasto Financiero',
            self::TYPE_OTHER          => 'Otro Gasto',
            default                   => 'Sin clasificar',
        };
    }

    // ✅ Color para badges en vistas
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_COST           => 'amber',
            self::TYPE_OPERATIONAL    => 'blue',
            self::TYPE_ADMINISTRATIVE => 'purple',
            self::TYPE_FINANCIAL      => 'rose',
            self::TYPE_OTHER          => 'zinc',
            default                   => 'zinc',
        };
    }

    // ✅ ¿Es costo de producción?
    public function getIsCostAttribute(): bool
    {
        return $this->type === self::TYPE_COST;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Filtrar por tipo genérico
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ✅ Scopes específicos por tipo
    public function scopeCosts($query)
    {
        return $query->where('type', self::TYPE_COST);
    }

    public function scopeOperational($query)
    {
        return $query->where('type', self::TYPE_OPERATIONAL);
    }

    public function scopeAdministrative($query)
    {
        return $query->where('type', self::TYPE_ADMINISTRATIVE);
    }

    public function scopeFinancial($query)
    {
        return $query->where('type', self::TYPE_FINANCIAL);
    }

    public function scopeOther($query)
    {
        return $query->where('type', self::TYPE_OTHER);
    }

    // Solo las que tienen cuenta PUC configurada
    public function scopeWithPuc($query)
    {
        return $query->whereNotNull('puc_code')->where('puc_code', '!=', '');
    }

    // Las que NO tienen PUC
    public function scopeWithoutPuc($query)
    {
        return $query->whereNull('puc_code')->orWhere('puc_code', '');
    }
}