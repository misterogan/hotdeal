<?php
namespace App\Helpers;

use App\Category;
use App\MediaLog;
use App\Notification;
use App\NotificationDetail;
use App\OrderDetailProduct;
use App\SpecialEvent;
use App\TicketRaffle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Throwable;

class RaffleTicket {

    private $category_product_dev = [
        '83' => 1,
        '84' => 2,
        '85' => 3
    ];
    private $category_product_live = [
        '81' => 1,
        '90' => 2,
        '91' => 3,
    ];

    private $category_product_local = [
        '83' => 1,
        '84' => 2,
        '85' => 3
    ];

    public function generate_ticket($event_id , $total_payment){

    }

    public static function total_raffle_ticket($total_payment , $status){
        if($status == 'bundling'){
            if($total_payment >= 100000 && $total_payment < 300000){
                return 3;
            }elseif($total_payment >= 300000 && $total_payment < 500000){
                return 5;
            }elseif($total_payment >= 500000){
                return 10;
            }
        }else{
            if($total_payment < 100000){
                return 0;
            }else{
                return floor($total_payment / 100000);
            }
        }
        return 0;
    }

    public static function active_event(){
        $event = SpecialEvent::where('status' , 'active')->first();
        if($event){
            return $event;
        }
        return false;
    }

    public static function order_by_order_id($order_id){
        $order  = OrderDetailProduct::select('order_detail_products.fix_price','products.category_id','orders.point','orders.total_discount', 'orders.total_payment', 'orders.user_id','order_detail_products.quantity', 'orders.transaction_number')
            ->leftJoin('order_details' , 'order_details.id' , 'order_detail_products.order_detail_id')
            ->leftJoin('orders' , 'orders.id' , 'order_details.order_id')
            ->leftJoin('product_details' , 'order_detail_products.product_detail_id' , 'product_details.id')
            ->leftJoin('products' , 'products.id' , 'product_details.product_id')
            ->where('orders.id' , $order_id)
            ->get();
        if(!$order){
            return false;
        }
        return $order;
    }

    public function category_product($id){
        if(env('APP_ENV') == 'production'){
            if(array_key_exists($id , $this->category_product_live)){
                return $this->category_product_live[$id];
            }
        }elseif(env('APP_ENV') == 'staging'){
            if(array_key_exists($id , $this->category_product_dev)){
               return $this->category_product_dev[$id];
            }
        }elseif(env('APP_ENV') == 'local'){
            if(array_key_exists($id , $this->category_product_local)){
                return $this->category_product_local[$id];
             }
        }elseif(env('APP_ENV') == 'testing'){
             if(array_key_exists($id , $this->category_product_local)){
                return $this->category_product_local[$id];
             }
        }
       return 0;
    }

    public function category_of_raffle_ticket(){
        if(env('APP_ENV') == 'production'){
            return array_keys($this->category_product_live);
        }elseif(env('APP_ENV') == 'staging'){
            return array_keys($this->category_product_dev);
        }elseif(env('APP_ENV') == 'local'){
            return array_keys($this->category_product_local);
        }elseif(env('APP_ENV') == 'testing'){
            return array_keys($this->category_product_local);
        }
    }


}
