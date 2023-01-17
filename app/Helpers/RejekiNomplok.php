<?php
namespace App\Helpers;

use App\MediaLog;
use App\Notification;
use App\NotificationDetail;
use App\OrderDetail;
use App\RejekiNomplokCoupon;
use App\RejekiNomplokProduct;
use App\RejekiNomplokWeek;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Throwable;

class RejekiNomplok {
    public static function create_coupon($invoice_number, $user_id){
        $order_detail = OrderDetail::where('invoice_number', $invoice_number)->with('order_products.product_rejeki_nomplok')->first();
        if($order_detail){
            foreach($order_detail->order_products as $key=>$val){
                $is_rejeki_nomplok = RejekiNomplokProduct::where('product_id', $val->product_rejeki_nomplok->product_id)->where('status', 'active')->first();
                if ($is_rejeki_nomplok) {
                    $now = Carbon::now();
                    $weekStartDate = $now->startOfWeek()->format('Y-m-d');
                    $weekEndDate = $now->endOfWeek()->format('Y-m-d');
                    $week = RejekiNomplokWeek::orderBy('week' , 'ASC')->where('start_date', '>=' , $weekStartDate)->where('end_date', '<=' ,$weekEndDate)->first();
                    if(!$week){
                        $week = RejekiNomplokWeek::create([
                            // 'week' => (int)((date('Y').RejekiNomplokWeek::where('end_date' , '<' , date('Y').'-01-01')->count() + 1)), 
                            'week' => (int)(RejekiNomplokWeek::where('status', 'active')->orderByDesc('id')->pluck('week')->first()) + 1,
                            'start_date' => $weekStartDate, 
                            'end_date'=> $weekEndDate, 
                            'status' => 'active', 
                            'created_at' => Carbon::now(), 
                            'updated_at' => Carbon::now(), 
                            'created_by' => 'sistem', 
                            'updated_by' => 'sistem'
                        ]);
                    }
                    //echo $val->quantity; exit;
                    for($i = 0; $i < $val->quantity; $i++) {
                        $coupon = RejekiNomplokCoupon::orderByDesc('id')->where('rejeki_nomplok_week_id' , $week->id)->first();
                        if ($coupon) {
                            if ($coupon->coupon_number < 9) {
                                $coupon_number = $coupon->coupon_number + 1;
                            } else {
                                $coupon_number = 0;
                            }
                        } else {
                            $coupon_number = 0;
                        }
                        RejekiNomplokCoupon::create([
                            'user_id' => $user_id,
                            'rejeki_nomplok_week_id' => $week->id,
                            'order_details_id' => $order_detail->id,
                            'product_id' => $val->product_rejeki_nomplok->product_id,
                            'coupon_number' => $coupon_number,
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
        }
    }


}
