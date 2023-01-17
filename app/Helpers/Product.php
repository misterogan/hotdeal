<?php
namespace App\Helpers;
use App\OrderDetail;
use App\ViewProduct;

class Product {
    public static function stock($product_detail_id , $quantity) {
        $product = ViewProduct::select('stock')->where('product_detail_id',$product_detail_id)->first();
        if($product->stock >= $quantity){
            return true;
        }else{
            return false;
        }
    }

    public static function is_that_you($user_id,$invoice_number){
        $order_details = OrderDetail::leftJoin('orders',function($join){
            $join->on('order_details.order_id','=','orders.id');
        })->where('order_details.invoice_number',$invoice_number)->first([
            'orders.user_id',
        ]);

        if($user_id == $order_details->user_id){
            return true;
        }else{
            return false;
        }

    }
}
