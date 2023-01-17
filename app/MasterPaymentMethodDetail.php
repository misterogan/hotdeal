<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterPaymentMethodDetail extends Model
{
    protected $table = 'master_payment_method_details';
    protected $fillable = [
        'id', 'name','payment_method_id','code','is_activated','payment_gateway','created_at', 'updated_at'
    ];
}
