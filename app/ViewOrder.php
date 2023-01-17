<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewOrder extends Model
{
    protected $table = 'view_orders';

    protected $fillable = [
        'user_order_id','transaction_number','total_payment','total_discount','order_date','invoice_number','invoice_total_payment',
        'invoice_total_discount','name','user_vendor_id','vendor_image','order_status_id','description','quantity','price','discount_price','fix_price',
        'product_detail_id','link'
    ];
}
