<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderDetailShipping extends Model
{
    protected $table = 'order_detail_shipping';

    protected $dates = [
        'created_at',
        'updated_at',
       
    ];

    
    protected $fillable = [
        'logistic_detail','order_details_id', 'user_addresses_id', 'shipment_services_id', 'promotion_free_shipments_id', 'consignee','consigner','shipping_cost', 'created_at', 'updated_at','rate','min_estimate_arrived','max_estimate_arrived','shipment_'
        ,'order_id','logistic_name','shipment_code','label','awb_number'
    ];

    public function getPickupTimeAttribute($date)
    {
        if($date){
            return date('d F Y H:i' , strtotime($date));
        }
        return null;
    }

    public function user_address() {
        return $this->hasOne(UserAddress::class, 'id', 'user_addresses_id');
    }

    public function services() {
        return $this->hasOne(ShipmentService::class, 'id', 'shipment_services_id');
    }
}
