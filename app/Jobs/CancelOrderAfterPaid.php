<?php

namespace App\Jobs;

use App\Helpers\OrderDetails;
use App\Helpers\Orders;
use App\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelOrderAfterPaid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {   
        //
        $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = OrderDetail::where('order_id' , $this->order_id)->with('status_order')->get();
        if($order){
            foreach($order as $item){
                if($item->status_order->status_code == 'pending'){
                    $order_details = new OrderDetails($item , 'hotdeal_canceled');
                    if(!$order_details->cancelStatusAfterPaidBySystem()){
                        return false;
                    }
                }
            }
        }
    }
}
