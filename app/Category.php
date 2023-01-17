<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'parent_id', 'category', 'is_parent','image', 'icon', 'status','slug','role','show_in_menu','created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    function products() {
        return $this->hasMany(Product::class, 'category_id', 'id')->where('status','active');
    }

    function children() {
        return $this->hasMany($this, 'parent_id')->where('is_parent', false)->where('status', 'active');
    }

    function parent() {
        return $this->hasOne($this, 'id', 'parent_id')->where('is_parent', true);
    }

    public function scopeActive($query, $vendor_id)
    {
        return $query->whereHas('products', function($q) use ($vendor_id){
            $q->where('vendor_id', $vendor_id);
        });
    }
}
