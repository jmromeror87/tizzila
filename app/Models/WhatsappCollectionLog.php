<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappCollectionLog extends Model
{
    protected $table = 'whatsapp_collection_logs';

    protected $fillable = [
        'customer_id', 'invoice_id', 'phone', 'message', 'type', 'sent', 'error',
    ];

    protected $casts = ['sent' => 'boolean'];

    public function customer() { return $this->belongsTo(\App\Models\Customer\Customer::class); }
    public function invoice()  { return $this->belongsTo(\App\Models\Invoice::class); }
}
