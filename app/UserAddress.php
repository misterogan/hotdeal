<?php

namespace App;

use App\Cities;
use App\Province;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id','recipient_name', 'phone_number', 'address', 'zip_code', 'label_name','is_primary_address','status','created_at','updated_at','province_id','city_id','regency_id','area_id','lng','lat'
    ];

    public function province(){
        return $this->hasOne(Province::class,'id','province_id')->select('id','name','name as text');
    }
    public function city(){
        return $this->hasOne(Cities::class,'id','city_id')->select('id','name','name as text');
    }
    public function regency(){
        return $this->hasOne(Suburbs::class, 'id', 'regency_id')->select('id','name','name as text');
    }
    public function area(){
        return $this->hasOne(Area::class, 'id', 'area_id')->select('id','name','name as text');
    }

    public function area_point(){
        return $this->hasOne(Area::class, 'id', 'area_id');
    }
}
