<?php

namespace App\Jobs;

use App\Helpers\TransactionEmailTemplate;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailNotificationVendor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;
    protected $name;
    protected $email;
    protected $date;
    protected $invoice;
    protected $resi;
    protected $consigner;
    protected $consignee;
    protected $total_payment;
    protected $shipping;
    protected $shipping_price;
    protected $status;
    protected $view;
    protected $subject;

    protected $transaction_number;
    protected $type;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    //public function __construct($products , $name , $email , $date , $invoice , $resi , $consigner , $consignee , $total_payment , $shipping , $shipping_price , $status, $view , $subject)
    public function __construct($transaction_number , $type)
    {
        $this->transaction_number =  $transaction_number;
        $this->type = $type;
        // $this->products = $products;
        // $this->name = $name;
        // $this->email = $email;
        // $this->date = $date;
        // $this->invoice = $invoice;
        // $this->resi = $resi;
        // $this->consigner = $consigner;
        // $this->consignee = $consignee;
        // $this->total_payment = $total_payment;
        // $this->shipping = $shipping;
        // $this->shipping_price = $shipping_price;
        // $this->status = $status;
        // $this->view = $view;
        // $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(config('app.env') != 'production'){
            return ;
        }
        $transaction = Order::
            with(['user','order_detail','order_detail.shipping','order_detail.vendor','order_detail.vendor.user','order_detail.productswithdetailwithimage','payment','payment.paymentMethod','master_status'])
            ->where('transaction_number' , $this->transaction_number)->first();

        if($transaction){
            if( $this->type == 'paid'){
                foreach($transaction->order_detail as $item){
                    $name = $item->vendor->user->name;
                    $email = $item->vendor->user->email;
                    $subject = 'HOTDEAL - PAYMENT ACCEPTED';
                    
                    $data = ['transaction' => $item, 'body' => str_replace(
                        array("{user_name}" ,"{nomor_invoice}"),
                        array($transaction->user->name, $item->invoice_number),
                        TransactionEmailTemplate::vendor_body_email('paid' , '2022'))];
                    try {
                        Mail::send('emails.vendor', $data, function ($message) use ($name, $email, $subject) {
                            $message->to($email, $name)->subject($subject);
                            $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                        });
                    } catch (\Exception $e) {
                        return $e->getMessage();
                    }
                }
        
            }elseif($this->type == 'delivered'){
                foreach($transaction->order_detail as $item){
                    $name = $item->vendor->user->name;
                    $email = $item->vendor->user->email;
                    $subject = 'HOTDEAL - SHIPPED';
                    $data = ['transaction' => $item, 'body' => TransactionEmailTemplate::vendor_body_email('delivered' , '2022')];
                    try {
                        Mail::send('emails.vendor', $data, function ($message) use ($name, $email, $subject) {
                            $message->to($email, $name)->subject($subject);
                            $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                        });
                    } catch (\Exception $e) {
                        return $e->getMessage();
                    }

                }
            }elseif($this->type == 'delivery'){
                foreach($transaction->order_detail as $item){
                    $name = $item->vendor->user->name;
                    $email = $item->vendor->user->email;
                    $subject = 'HOTDEAL - SHIPPED';
                    $data = ['transaction' => $item, 'body' => TransactionEmailTemplate::vendor_body_email('delivery' , '2022')];
                    try {
                        Mail::send('emails.vendor', $data, function ($message) use ($name, $email, $subject) {
                            $message->to($email, $name)->subject($subject);
                            $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                        });
                    } catch (\Exception $e) {
                        return $e->getMessage();
                    }
                }
            }
        }
    }
}
