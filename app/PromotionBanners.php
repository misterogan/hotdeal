<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionBanners extends Model
{
    protected $table = 'promotion_banners';

    protected $fillable = [
        'id', 'banner', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
