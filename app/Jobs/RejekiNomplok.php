<?php

namespace App\Jobs;

use App\OrderDetail;
use App\RejekiNomplokCoupon;
use App\RejekiNomplokProduct;
use App\RejekiNomplokWeek;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RejekiNomplok implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $invoice_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoice_id)
    {
        //
        $this->invoice_id = $invoice_id;        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_detail = OrderDetail::where('invoice_number', $this->invoice_id)->with('order_products.product')->first();
        $order_product = $order_detail->order_products->product->id;
        $is_rejeki_nomplok = RejekiNomplokProduct::where('product_id', $order_product)->first();
        if ($is_rejeki_nomplok) {
            $week = RejekiNomplokWeek::orderByDesc('id')->where('status', 'active')->first();
            for($i = 0; $i < $order_detail->order_products->quantity; $i++) {
                $coupon = RejekiNomplokCoupon::orderByDesc('id')->first();
                if ($coupon) {
                    $coupon_number = $coupon->coupon_number + 1;
                } else {
                    $coupon_number = 0;
                }
                $reward_coupon = RejekiNomplokCoupon::create([
                    'user_id' => $user->id,
                    'rejeki_nomplok_week_id' => $week->id,
                    'order_details_id' => $order_detail->id,
                    'product_id' => $order_detail->order_products->product->id,
                    'coupon_number' => $coupon_number,
                    'status' => 'active',
                    'created_at' => Carbon::now(),
                    'updated_at' => $user->name,
                ]);
            }
        }
    }
}
