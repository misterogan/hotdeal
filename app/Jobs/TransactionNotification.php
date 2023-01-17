<?php

namespace App\Jobs;

use App\Helpers\Notify;
use App\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransactionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    // protected $title;
    // protected $body;
    // protected $url;
    // protected $image;
    // protected $send_to;
    // protected $topic;
    // protected $user_id;
    // protected $send_by;
    protected $invoice;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoice , $type)
    {
        //
        $this->invoice = $invoice;
        $this->type = $type;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type == 'vendor_canceled'){
            $body = Notify::$order_cancel_body_vendor;
        }elseif($this->type == 'hotdeal_canceled'){
            $body = Notify::$order_cancel_body_auto;
        }else{
            $body = Notify::$order_cancel_body_general;
        }
        $user_id = OrderDetail::where('invoice_number' ,$this->invoice)->with('order')->first();
        Notify::send(
            Notify::$order_cancel_title , 
            str_replace(
                array("{invoice_number}"),
                array($this->invoice),
                $body)
            , '/transactions/list-transaction' , '' , 'uid' , Notify::$PAYMENT_TOPIC , $user_id->order->user_id , "Hotdeal"
        );
        Notify::send(
            Notify::$order_cancel_title , 
            str_replace(
                array("{invoice_number}"),
                array($this->invoice),
                $body)
            , '/transactions/list-transaction' , '' , 'uid' , Notify::$PAYMENT_TOPIC , '0' , "Hotdeal"
        );
    }
}
