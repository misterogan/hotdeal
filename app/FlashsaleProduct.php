<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashsaleProduct extends Model
{
    protected $table = 'flashsale_products';

    protected $fillable = [
        'name','img_url','normal_prize', 'discounted_prize', 'weight', 'quantity', 'is_free_shipping', 'status', 'shop_id', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
