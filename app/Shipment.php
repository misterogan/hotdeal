<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';

    protected $fillable = [
        'id','shipper_shipment_id','shipment_name', 'logo_url', 'status','code', 'created_at', 'updated_at', 'created_by', 'updated_by','server'
    ];

    public function services(){
        return $this->hasMany(ShipmentService::class,'shipment_services_id','id');
    }
}
