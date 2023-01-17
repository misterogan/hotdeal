<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterPaymentMethod extends Model
{
    protected $table = 'master_payment_methods';
    protected $fillable = [
        'id','payment_name','created_at', 'updated_at','created_by', 'updated_by'
    ];
}
