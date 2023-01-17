<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //
    protected $table = 'payment_methods';

    protected $fillable = [
         'label','channel','icon','status', 'created_at', 'updated_at'
    ];
}
