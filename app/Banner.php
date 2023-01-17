<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $fillable = [
        'name','img_url','img_url_mobile' ,'video_url','url','type','placement','new_tab','sequence','status','created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
