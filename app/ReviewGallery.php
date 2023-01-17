<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewGallery extends Model
{
    protected $table = 'review_galleries';

    protected $fillable = [
        'id','review_id','status','type', 'url_source', 'created_at','updated_at',
    ];
}
