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

class AccountingSetting extends Model
{
    protected $fillable = [
        'key_name',
        'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    // 🔥 Claves del sistema (ERP)
    public const KEYS = [

    // 💰 CAJA Y BANCOS
    'cash' => 'Caja',
    'bank' => 'Banco',

    // 💸 GASTOS
    'expense_default' => 'Gasto General',
    'expense_services' => 'Gastos de Servicios',
    'expense_rent' => 'Arrendamientos',
    'expense_payroll' => 'Nómina',
    'expense_maintenance' => 'Mantenimiento',

    // 🧾 IMPUESTOS
    'iva_creditable' => 'IVA Descontable (Compras)',
    'iva_generated' => 'IVA Generado (Ventas)',
    'retefuente' => 'Retención en la Fuente',
    'reteica' => 'Retención ICA',

    // 🧾 CUENTAS POR PAGAR
    'accounts_payable' => 'Cuentas por Pagar',
    'suppliers' => 'Proveedores',

    // 💵 CUENTAS POR COBRAR
    'accounts_receivable' => 'Cuentas por Cobrar',
    'customers' => 'Clientes',

    // 💰 INGRESOS
    'income_sales' => 'Ingresos por Ventas',
    'income_services' => 'Ingresos por Servicios',

    // 📦 INVENTARIO
    'inventory' => 'Inventario',
    'cost_of_sales' => 'Costo de Ventas',

    // 🏦 PATRIMONIO
    'equity' => 'Patrimonio',
    'retained_earnings' => 'Utilidades Retenidas',

    // ⚙️ SISTEMA
    'rounding' => 'Ajuste por Redondeo',
];
}