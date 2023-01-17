<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentLog extends Model
{
    protected $table = 'shipment_logs';

    protected $fillable = [
        'id','payload','shipment_source','created_at','updated_at'
    ];
}
