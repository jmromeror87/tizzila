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
