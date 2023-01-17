<?php
namespace App\Helpers;

use App\MasterStatusOrder;
use App\OrderDetail;
use App\OrderDetailLog;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Order;
use App\RejekiNomplokCoupon;
use Illuminate\Support\Facades\Log;

class CouponsRejekiNomplok {

    public static function getCouponById(){

    }
    public static function getLastNumberIhsg($string){
        return substr($string, -1);
    }
    public static function InfoTotalPaybackAndTotalWinnerByWeekId($weekId , $ihsg){
        $coupon = self::getLastNumberIhsg($ihsg);
        $data = RejekiNomplokCoupon::select(DB::raw('count(rejeki_nomplok_coupons.id) as total_winner') , DB::raw('SUM(order_detail_products.fix_price) as total_payback'))
                ->join('order_detail_products' , 'rejeki_nomplok_coupons.order_details_id' ,'order_detail_products.id')
                ->join('products' , 'products.id' ,'rejeki_nomplok_coupons.product_id')
                ->where('rejeki_nomplok_week_id' , $weekId)
                ->where('rejeki_nomplok_coupons.coupon_number' ,$coupon)
                ->first();
        return $data;
    }

}
