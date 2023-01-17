<?php

namespace App\Jobs;

use App\Helpers\OrderDetails;
use App\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelOrderAfterProcessed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $invoice_number;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoice_number)
    {
        //
        $this->invoice_number = $invoice_number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = OrderDetail::where('invoice_number' , $this->invoice_number)->with('status_order')->first();
        if($order){
            if($order->status_order->status_code == 'processed'){
                $order_details = new OrderDetails($order , 'hotdeal_canceled');
                if(!$order_details->cancelStatusAfterProcessedBySystem()){
                    return false;
                }
            }
        }
    }
}
