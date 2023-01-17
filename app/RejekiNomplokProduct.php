<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejekiNomplokProduct extends Model
{
    protected $table = 'rejeki_nomplok_products';

    protected $fillable = [
        'id', 'product_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
