<?php

namespace App\Http\Resources;

use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\Product;
use App\ProductDetail;
use App\TicketRaffle;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListTicketResource extends JsonResource
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
            //'id' => $this->id,
            'detail' => $this->detail,
            'transaction' => $this->transaction,
            // 'date' => $this->created_at->format('Y-m-d'),
            // 'total_ticket_raffle' => $this->total_ticket($this->id),
            // 'order_detail' => $this->detail($this->order_id)
        ];
    }

    private function detail($id){
        $data = [];
        $total_payment = Order::where('id', $id)->pluck('total_payment')->first();
        $order_detail = OrderDetail::where('order_id', $id)->get();
        foreach($order_detail as $value){
            $detail_id = $value->id;
            $product_detail_id = OrderDetailProduct::where('order_detail_id', $detail_id)->pluck('product_detail_id')->first();
            $product_id = ProductDetail::where('id', $product_detail_id)->pluck('product_id')->first();
            $products = Product::where('status', 'active')->where('id', $product_id)->get();
            $display_product = $products->pluck('name')->first();
            $data[] = [
                'invoice' => $value->invoice_number,
                'total_payment' => $total_payment,
                'products' => $products,
                'other_product_total' => $products->count('id') - 1,
                'display_product' =>$display_product
            ];
        }

        return $data;
    }

    private function total_ticket($id){
        $total = TicketRaffle::where('id', $id)->where('status', 'active')->count('id');
        return $total;
    }
}
