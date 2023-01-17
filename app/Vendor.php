<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'name', 'user_id', 'image', 'country_id', 'province_id','pic', 'city_id', 'area_id', 'lat', 'lng', 'suburb_id','address', 'status', 'description', 'rating', 'created_at', 'updated_at', 'created_by', 'updated_by','active_sameday','slug'
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function country() {
        return $this->hasOne(Countries::class, 'id', 'country_id');
    }

    public function city() {
        return $this->hasOne(Cities::class, 'api_id', 'city_id');
    }

    public function province() {
        return $this->hasOne(Province::class, 'api_id', 'province_id');
    }

    public function suburb() {
        return $this->hasOne(Suburbs::class, 'api_id', 'suburb_id');
    }

    public function area() {
        return $this->hasOne(Area::class, 'api_id', 'area_id');
    }
}
