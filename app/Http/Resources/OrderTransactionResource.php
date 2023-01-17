<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTransactionResource extends JsonResource
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
            'id'=>$this->id,
            'transaction_number'=>$this->transaction_number,
            'total_payment'=>$this->total_payment,
            'total_discount'=>$this->total_discount,
            'status'=>$this->status,
            'order_date'=>date("d/m/Y", strtotime($this->created_at)),
            'order_payment'=> new OrderPaymentResource($this->order_payments),
            'order_history'=> OrderHistoryResource::collection($this->order_history),
        ];

    }
}
