<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefundStatus extends Model
{
    protected $table = 'refund_status';
    protected $visible = ['description_vendor','description','updated_at', 'status'];
    protected $fillable = [
        'id', 'description', 'description_vendor', 'status', 'created_at','updated_at'
    ];
}
