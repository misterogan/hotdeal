<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionFeature extends Model
{
    protected $table = 'promotion_features';

    protected $fillable = [
        'feature_name', 'description', 'link', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
