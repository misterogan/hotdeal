<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';

    protected $fillable = [
        'order_id','external_id','bank_code','name','expected_amount','is_closed','expiration_date','is_single_true','status','currency','owner_id','merchant_code','account_number','id_payment','payment_gate'
    ];

    public function paymentMethod() {
        return $this->hasOne(PaymentMethod::class, 'code' , 'bank_code');
    }

    public function bankCode(){
       // return $this->hasOne()
    }

}
