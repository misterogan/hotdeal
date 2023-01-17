<?php
namespace App\Helpers;

use App\Notification;
use App\NotificationDetail;
use App\Order;
use App\Page;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\ProductDetail;
use Carbon\Carbon;
use http\Cookie;
use Illuminate\Support\Facades\Cache;
use Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductUtils {
    public static function sold_product_by_id($product_id){
        $sold = OrderDetailProduct::select(DB::raw('sum(order_detail_products.quantity) as quantity'))
        ->join('order_details' , 'order_details.id' , 'order_detail_products.order_detail_id')
        ->join('product_details' , 'order_detail_products.product_detail_id' , 'product_details.id')
        ->where('product_details.product_id' , $product_id)
        ->where('order_details.status' , '5')
        ->first();
        $data['sold'] = 0;
        $data['text'] = '';
        if($sold){
            $data['sold'] = $sold->quantity;
            $data['text'] = Utils::make_human_number($sold->quantity);
        }
        return $data;
    }

    public static function sold_flashsale($product_id){
        $total_stock = 0;
        $sold = OrderDetailProduct::select(DB::raw('sum(order_detail_products.quantity) as quantity'))
            ->join('order_details' , 'order_details.id' , 'order_detail_products.order_detail_id')
            ->join('product_details' , 'order_detail_products.product_detail_id' , 'product_details.id')
            ->where('product_details.product_id' , $product_id)
            ->whereIn('order_details.status' , ['5', '1'])
            ->sum('order_detail_products.quantity');
        $stock = ProductDetail::where('product_id', $product_id)->where('status', 'active')->sum('stock');
        $total_stock = $sold + $stock;
        // item.stock === 0 ? '0' : 100 - ((item.sold.sold / item.stock) * 100);
        $data['indicator'] = 0;
        if($total_stock != 0){
            $indicator = 100 - (($sold / $total_stock) * 100);
            $data['indicator'] = $indicator;
        }
        return $data;
    }
}
