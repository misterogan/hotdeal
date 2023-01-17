<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RefundLogs extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'refund_logs';

    protected $visible = ['created_at','updated_at','descriptions','description'];

    protected $fillable = [
        'id', 'refund_status_id','refund_id', 'description', 'created_at','updated_at'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->setTimezone('Asia/Jakarta')->translatedFormat('j F Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->translatedFormat('j F Y H:i:s');
    }
    
    public function status() {
        return $this->hasOne(RefundStatus::class, 'id', 'status_refund_id');
    }
    public function descriptions() {
        return $this->hasOne(RefundStatus::class, 'id' ,'refund_status_id');
    }
}
