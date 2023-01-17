<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    //protected $visible = ['created_at','updated_at','description'];

    protected $table = 'refunds';

    protected $fillable = [
        'id', 'user_id', 'refund_status_id', 'refund_type', 'order_details_id', 'description','image_1', 'image_2', 'image_3', 'video', 'created_at', 'updated_at'
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
        return $this->hasOne(RefundStatus::class, 'id', 'refund_status_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function transaction() {
        return $this->hasOne(OrderDetail::class, 'id', 'order_details_id');
    }

    public function detail() {
        return $this->belongsTo(OrderDetail::class, 'order_details_id', 'id');
    }

    public function logs(){
        return $this->hasMany(RefundLogs::class , 'refund_id' )->with('descriptions')->orderBy('id' ,'DESC');
    }
    public function refund_confirmation(){
        return $this->hasOne(RefundReturnConfirmation::class , 'refund_id' );
    }
    public function bank_account(){
        return $this->hasOne(RefundAccountBank::class , 'refund_id' );
    }
}
