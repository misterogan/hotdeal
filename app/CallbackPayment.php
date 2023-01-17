<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallbackPayment extends Model
{
    protected $table = 'callback_payments';
    protected $fillable = [
        'external_id','log','payment_gate','created_at','updated_at'
    ];
}
