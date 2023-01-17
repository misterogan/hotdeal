<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $table = 'flash_sales';

    protected $fillable = [
        'name', 'banner_type', 'banner', 'banner_mobile', 'start_date', 'end_date', 'status', 'slug', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    function flash_sale_detail(){
        return $this->hasMany(FlashSaleDetail::class, 'flash_sale_id');
    }

}
