<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponCustomerResource extends JsonResource
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
            'coupon_number' => $this->coupon_number,
            'start_date'=>$this->week->start_date,
            'end_date'=>$this->week->end_date,
            'status'=>$this->status,
            'invoice'=>$this->order->invoice_number,
            'product' => $this->product->name,
            'is_winner' => $this->is_winner,
            'order_date' => Carbon::parse($this->order->created_at)->translatedFormat('j F Y'),
            'created' => Carbon::parse($this->created_at)->translatedFormat('j F Y'),
        ];
    }
}
