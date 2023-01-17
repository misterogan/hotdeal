<?php

namespace App\Http\Resources;

use App\OrderDetailProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class RejekiNomplokCouponsResources extends JsonResource
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
            'id' => $this->id,
            'coupon_number'=>$this->coupon_number,
            'created_at'=> date( 'Y-m-d H:i:s' , strtotime($this->created_at) ),
            'has_send_point' => $this->has_send_point,
            'order' => $this->order,
            'point_sent' => $this->point_sent,
            'user' => $this->user,
            'week' => $this->week,
            'status' => $this->status,
            'product' => $this->product,
            'updated_at' =>date( 'Y-m-d H:i:s' , strtotime($this->updated_at) ),
            'has_send_point' => $this->has_send_point,
            'price' => $this->get_product_rejecki_nomplok($this->order_details_id , $this->product->id , $this->order->product->product_detail_id)

        ];
    }

    public function get_product_rejecki_nomplok($order_detail , $product_id , $product_detail_id){
        $fixprice = OrderDetailProduct::select('order_detail_products.fix_price')
                    ->leftJoin('product_details' , 'order_detail_products.product_detail_id' ,'product_details.id')
                    ->leftJoin('products' , 'products.id' ,'product_details.product_id')
                    ->where('products.id' ,  $product_id)
                    ->where('product_details.id' ,  $product_detail_id)
                    ->where('order_detail_products.order_detail_id' ,  $order_detail)->first();
        return $fixprice ? $fixprice->fix_price : 0;            
    }
}
