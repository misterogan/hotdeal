<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterStatusOrder extends Model
{
    protected $table = 'master_status_order';

    protected $fillable = [
        'status_code','period','description','status', 'created_at', 'updated_at','created_by', 'updated_by'
    ];
}
