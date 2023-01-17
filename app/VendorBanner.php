<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorBanner extends Model
{
    protected $table = 'vendor_banners';
    protected $fillable = [
        'id', 'vendor_id', 'img_url', 'status', 'url', 'new_tab', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
