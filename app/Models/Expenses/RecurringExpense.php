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
use Carbon\Carbon;

class RecurringExpense extends Model
{
    use HasFactory;

    protected $table = 'recurring_expenses';

    protected $fillable = [
        'provider_id',
        'company_id',
        'expense_category_id',
        'name',
        'description',
        'amount',
        'frequency',
        'start_date',
        'end_date',
        'next_run_date',
        'last_run_date',
        'is_active',
    ];

    protected $casts = [
        'company_id'    => 'integer',  // ✅ FIX 1: faltaba
        'provider_id'   => 'integer',
        'amount'        => 'decimal:2',
        'is_active'     => 'boolean',
        'start_date'    => 'date',
        'end_date'      => 'date',
        'next_run_date' => 'date',
        'last_run_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function provider()
    {
        return $this->belongsTo(\App\Models\Poultry\Provider::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'recurring_expense_id');
    }

    /*
    |--------------------------------------------------------------------------
    | LÓGICA ERP
    |--------------------------------------------------------------------------
    */

    public function calculateNextRunDate(): Carbon
    {
        $date = Carbon::parse($this->next_run_date);

        return match ($this->frequency) {
            'daily'     => $date->addDay(),
            'weekly'    => $date->addWeek(),
            'biweekly'  => $date->addDays(15),
            'monthly'   => $date->addMonth(),
            default     => $date->addMonth(),
        };
    }

    // ✅ ¿Debe ejecutarse hoy?
    public function isDue(): bool
    {
        return $this->is_active
            && !$this->isExpired()
            && $this->next_run_date <= now()->toDateString();
    }

    // ✅ FIX 2: ¿Ya venció? (end_date en el pasado)
    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date < now()->toDateString();
    }

    // ✅ ¿Próximo a ejecutarse? (dentro de N días)
    public function isUpcoming(int $days = 3): bool
    {
        $diff = now()->diffInDays($this->next_run_date, false);

        return $this->is_active
            && !$this->isExpired()
            && $diff >= 0
            && $diff <= $days;
    }

    // ✅ NUEVO — ¿Cuántos días faltan para la próxima ejecución?
    public function daysUntilNextRun(): int
    {
        return (int) now()->diffInDays($this->next_run_date, false);
    }

    // ✅ NUEVO — Avanzar al siguiente ciclo (llamar después de generar el gasto)
    // Uso: $recurring->advanceToNextRun()->save();
    public function advanceToNextRun(): static
    {
        $this->last_run_date = now()->toDateString();
        $this->next_run_date = $this->calculateNextRunDate()->toDateString();

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // ✅ FIX 3: isDue() como scope para queries en DB
    // Uso: RecurringExpense::due()->get()
    public function scopeDue($query)
    {
        return $query->where('is_active', true)
            ->where('next_run_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            });
    }

    // ✅ NUEVO — próximos a vencer en N días
    // Uso: RecurringExpense::upcoming(7)->get()
    public function scopeUpcoming($query, int $days = 3)
    {
        return $query->where('is_active', true)
            ->whereBetween('next_run_date', [
                now()->toDateString(),
                now()->addDays($days)->toDateString(),
            ])
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            });
    }

    // ✅ NUEVO — los ya vencidos (end_date pasado)
    public function scopeExpired($query)
    {
        return $query->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString());
    }

    // ✅ NUEVO — solo activos no vencidos
    public function scopeRunning($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            });
    }
}