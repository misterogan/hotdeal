<?php

namespace App\Http\Resources;

use App\PaymentMethod;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'va_number'=>$this->external_id,
            'bank_code'=>$this->bank_code,
            'order_user_name'=>$this->name,
            'mount'=>$this->expected_amount,
            'expiration_date'=>date("d/m/Y H:i:s", strtotime($this->expiration_date)),
            'account_number'=>$this->account_number,
            'channel_payment' => $this->payment_channel($this->bank_code)
        ];
    }

    public function payment_channel($bank_code){
        $channel = PaymentMethod::select('label' , 'channel')->where('code' ,$bank_code)->first();
        if($channel){
            return [
                        "payment_channel" => '('.$channel->channel.') - '.$channel->label,
                        "channel" => $channel->channel
                   ];
        }
        return [
            "payment_channel" => "",
            "channel" => ""
       ];
    }
}
