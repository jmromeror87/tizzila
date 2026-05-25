<?php

namespace App\Models\Poultry;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;

class PoultryOrderDistribution extends Model
{
    protected $table = 'poultry_order_distributions';

    protected $fillable = [
        'poultry_order_schedule_id',
        'customer_id',
        'quantity',
        'sale_price',
        'vaccine_price',
        'despique_price',
        'beak_condition',
        'observations',
    ];

    protected $casts = [
        'sale_price'    => 'decimal:2',
        'vaccine_price' => 'decimal:2',
        'despique_price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(PoultryOrderSchedule::class, 'poultry_order_schedule_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
