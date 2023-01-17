<?php

namespace App\Jobs;

use App\Helpers\Orders;
use App\Order;
use App\OrderDetail;
use App\RejekiNomplokCoupon;
use App\RejekiNomplokProduct;
use App\RejekiNomplokWeek;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessDeliveredOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $orderId;
    protected $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        //
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // invoice status must arrived
        $order_detail = OrderDetail::select('master_status_order.status_code' , 'order_details.*')
                        ->join('master_status_order' , 'master_status_order.id' , 'order_details.status')
                        ->where('order_details.id', $this->orderId)
                        ->where('master_status_order.status_code' , 'arrived')
                        ->with('order_products.product_rejeki_nomplok')
                        ->first();
            if($order_detail){
                try {
                    DB::beginTransaction();
                    $userId = Order::select('user_id')->where('id', $order_detail->order_id)->first();
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
                            
                            for($i = 0; $i < $val->quantity; $i++) {
                                $coupon = RejekiNomplokCoupon::orderByDesc('id')->where('rejeki_nomplok_week_id' , $week->id)->where('product_id' , $val->product_rejeki_nomplok->product_id)->first();
                                if ($coupon) {
                                    $coupon_number = $coupon->coupon_number + 1;
                                } else {
                                    $coupon_number = 0;
                                }
                                RejekiNomplokCoupon::create([
                                    'user_id' => $userId->user_id,
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
                    Orders::update_status_from_webhook($order_detail->id , 5);
                    //Orders::update_status_by_customer($order_detail->invoice_number , 'arrived' ,'completed');
                    DB::commit();
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();
                    print_r($th->getMessage());
                }
            }
            
        
    }
}
