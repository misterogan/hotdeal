<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentLog extends Model
{
    protected $table = 'order_payment_logs';

    protected $fillable = [
        'external_id','order_id','created_at','payload','response','updated_at','action'
    ];
}
