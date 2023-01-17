<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rebound extends Model
{
    protected $table = 'rebounds';

    protected $fillable = [
        'id', 'refund_table_id', 'logistic', 'receipt_number', 'consignor', 'created_at','updated_at'
    ];

    public function refund() {
        $this->belongsTo(Refund::class, 'refund_table_id', 'id');
    }
}
