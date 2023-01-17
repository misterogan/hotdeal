<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketRaffle extends Model
{
    protected $table = 'ticket_raffles';
    protected $fillable = [
        'id', 'special_event_id', 'user_id','order_id', 'created_by', 'ticket_number', 'status', 'is_winner', 'created_at', 'updated_at'
    ];

    public function special_event() {
        return $this->hasOne(SpecialEvent::class, 'id', 'special_event_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function order_detail(){
        return $this->hasManyThrough(
            OrderDetail::class,
            Order::class,
            'id',
            'order_id',
            'order_id',
            'id'
        );
    }

    public function order_product(){
        return $this->hasManyThrough(
            OrderDetailProduct::class,
            OrderDetail::class,
            'order_id',
            'order_detail_id',
            'order_id',
            'id'
        );
    }

}
