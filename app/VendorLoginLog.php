<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorLoginLog extends Model
{
    protected $table = 'vendor_login_logs';

    protected $fillable = [
        'vendor_id', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
