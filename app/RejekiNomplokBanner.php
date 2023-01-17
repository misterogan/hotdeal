<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejekiNomplokBanner extends Model
{
    protected $table = 'rejeki_nomplok_banners';

    protected $fillable = [
        'banner', 'banner_mobile', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
