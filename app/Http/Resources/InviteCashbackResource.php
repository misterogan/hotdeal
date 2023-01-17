<?php

namespace App\Http\Resources;

use App\Hotpoint;
use App\OrderDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InviteCashbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'email'=> $this->getChildEmail($this->detail),
            'register_date'=> $this->registered_date($this->detail),
            'transaction_date' => $this->order_date($this->detail),
            'cashback' => $this->getCashback($this->detail),
        ];
    }

    private function getChildEmail($detail){
        $inv = $this->invoice_number($detail);
        $order = $this->getOrder($inv);
        $email = User::where('id', $order->user_id)->pluck('email')->first();
        return $email;
    }

    private function getCashback($detail) {
        $cashback = Hotpoint::where('detail', $detail)->where('type', 'earn')->where('code', 'EFI001')->pluck('value')->first();
        return $cashback;
    }

    private function registered_date($detail) {
        $inv = $this->invoice_number($detail);
        $order = $this->getOrder($inv);
        $date = User::where('id', $order->user_id)->pluck('created_at')->first();
        return $date->format('d/m/Y');
    }

    private function order_date($detail) {
        $inv = $this->invoice_number($detail);
        $order = $this->getOrder($inv);
        return $order->created_at->format('d/m/Y');
    }

    private function invoice_number($detail) {
        $inv = substr($detail, strpos($detail, "#") + 1);    
        return $inv;
    }

    private function getOrder($inv) {
        $order = OrderDetail::where('invoice_number', $inv)
                ->join('orders', 'order_details.order_id', 'orders.id')->first();
    return $order;
    }
}
