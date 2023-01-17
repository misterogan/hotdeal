<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'method', 'name', 'billing_address', 'number', 'expiry_date', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
