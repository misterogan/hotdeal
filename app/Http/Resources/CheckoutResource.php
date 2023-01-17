<?php

namespace App\Http\Resources;

use App\Helpers\Utils;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
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
            'id_cart'=> $this->id,
            'user_id'=>$this->user_id,
            'quantity'=>$this->quantity,
            'in_checkout'=>$this->in_checkout,
            'price'=> Utils::currency_convert($this->products['face_price']),
        ];
    }

    private function total_payment(){

    }
}
