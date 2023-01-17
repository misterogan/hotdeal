<?php

namespace App\Jobs;

use App\Helpers\TransactionEmailTemplate;
use App\MediaLog;
use App\Order;
use App\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TransactionEmailNotification implements ShouldQueue
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
        if( $this->type == 'create_order'){
            $transaction = Order::
            with(['order_detail','order_detail.shipping','order_detail.productswithdetailwithimage' ,'user','payment','payment.paymentMethod','master_status'])
            ->where('transaction_number' , $this->transaction_number)->first();
            $name = $transaction->user->name;
            $email = $transaction->user->email;
            $subject = 'HOTDEAL - ORDER CONFIRMATION';
            $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('confirmation' , '2022')];
            try {
                Mail::send('emails.create_order', $data, function ($message) use ($name, $email, $subject) {
                    $message->to($email, $name)->subject($subject);
                    $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                });
            } catch (\Exception $e) {
                MediaLog::insert(['log' => $e->getMessage()]);
                return $e->getMessage();
            }
        }
        else if( $this->type == 'paid'){
            $transaction = Order::
            with(['order_detail','order_detail.shipping','order_detail.productswithdetailwithimage' ,'user','payment','payment.paymentMethod','master_status'])
            ->where('transaction_number' , $this->transaction_number)->first();
            $name = $transaction->user->name;
            $email = $transaction->user->email;
            $subject = TransactionEmailTemplate::$title_paid;  
            $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('paid' , '2022')];
            try {
                Mail::send('emails.create_order', $data, function ($message) use ($name, $email, $subject) {
                    $message->to($email, $name)->subject($subject);
                    $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                });
            } catch (\Exception $e) {
                MediaLog::insert(['log' => $e->getMessage()]);
                return $e->getMessage();
            }
        }
        else if( $this->type == 'accept'){
            $transaction = OrderDetail::with('order.user')
                        ->with('productswithdetailwithimage')
                        ->with('payment')
                        ->with('payment.paymentMethod')
                        ->with('shipping')
                        ->where('invoice_number' ,  $this->transaction_number)
                        ->first();
            $name = $transaction->order->user->name;
            $email = $transaction->order->user->email;
            $subject = TransactionEmailTemplate::$title_shipped;                    
            $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('accept' , '2022')];
            try {
                Mail::send('emails.update_order', $data, function ($message) use ($name, $email, $subject) {
                    $message->to($email, $name)->subject($subject);
                    $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                });
            } catch (\Exception $e) {
                print_r($e->getMessage()); 
            }
        }
        else if( $this->type == 'delivered'){
            $transaction = OrderDetail::with('order.user')
                        ->with('productswithdetailwithimage')
                        ->with('payment')
                        ->with('payment.paymentMethod')
                        ->with('shipping')
                        ->where('invoice_number' ,  $this->transaction_number)
                        ->first();
            $name = $transaction->order->user->name;
            $email = $transaction->order->user->email;
            $subject = TransactionEmailTemplate::$title_delivered;                    
            $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('delivered' , '2022')];
            try {
                Mail::send('emails.update_order', $data, function ($message) use ($name, $email, $subject) {
                    $message->to($email, $name)->subject($subject);
                    $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                });
            } catch (\Exception $e) {
                print_r($e->getMessage()); 
            }
        }
    }
}
