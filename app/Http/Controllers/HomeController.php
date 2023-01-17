<?php

namespace App\Http\Controllers;

use App\Helpers\Orders;
use App\Helpers\TransactionEmailTemplate;
use App\Jobs\EmailNotificationVendor;
use App\Jobs\ProcessDeliveredOrder;
use App\Jobs\TransactionEmailNotification;
use App\Order;
use App\OrderDetail;
use App\OrderDetailShipping;
use App\RejekiNomplokCoupon;
use App\RejekiNomplokProduct;
use App\RejekiNomplokWeek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function test_response(){
        // $order_id = '223KQZY35XE2V';
        // $get_order = OrderDetailShipping::where('order_id' , $order_id)->where('order_id' ,'!=', null)->first();
        // $order_detail = OrderDetail::select('master_status_order.status_code' , 'order_details.*')
        //                 ->join('master_status_order' , 'master_status_order.id' , 'order_details.status')
        //                 ->where('order_details.id',  $get_order->order_details_id)
        //                 ->where('master_status_order.status_code' , 'arrived')
        //                 ->with('order_products.product_rejeki_nomplok')
        //                 ->first();
        //     if($order_detail){
        //         try {
        //             DB::beginTransaction();
        //             $userId = Order::select('user_id')->where('id', $order_detail->order_id)->first();
        //             foreach($order_detail->order_products as $key=>$val){
        //                 $is_rejeki_nomplok = RejekiNomplokProduct::where('product_id', $val->product_rejeki_nomplok->product_id)->where('status', 'active')->first();
        //                 if ($is_rejeki_nomplok) {
        //                     $now = Carbon::now();
        //                     $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        //                     $weekEndDate = $now->endOfWeek()->format('Y-m-d');
        //                     $week = RejekiNomplokWeek::orderBy('week' , 'ASC')->where('start_date', '>=' , $weekStartDate)->where('end_date', '<=' ,$weekEndDate)->first();
        //                     if(!$week){
        //                         $week = RejekiNomplokWeek::create([
        //                             'week' => (int)((date('Y').RejekiNomplokWeek::where('end_date' , '<' , date('Y').'-01-01')->count() + 1)), 
        //                             'start_date' => $weekStartDate, 
        //                             'end_date'=> $weekEndDate, 
        //                             'status' => 'active', 
        //                             'created_at' => Carbon::now(), 
        //                             'updated_at' => Carbon::now(), 
        //                             'created_by' => 'sistem', 
        //                             'updated_by' => 'sistem'
        //                         ]);
        //                     }
                            
        //                     for($i = 0; $i < $val->quantity; $i++) {
        //                         $coupon = RejekiNomplokCoupon::orderByDesc('id')->where('rejeki_nomplok_week_id' , $week->id)->where('product_id' , $val->product_rejeki_nomplok->product_id)->first();
        //                         if ($coupon) {
        //                             $coupon_number = $coupon->coupon_number + 1;
        //                         } else {
        //                             $coupon_number = 0;
        //                         }
        //                         RejekiNomplokCoupon::create([
        //                             'user_id' => $userId->user_id,
        //                             'rejeki_nomplok_week_id' => $week->id,
        //                             'order_details_id' => $order_detail->id,
        //                             'product_id' => $val->product_rejeki_nomplok->product_id,
        //                             'coupon_number' => $coupon_number,
        //                             'status' => 'active',
        //                             'created_at' => Carbon::now(),
        //                             'updated_at' => Carbon::now(),
        //                         ]);
        //                     }
        //                 }
        //             }
        //             Orders::update_status_from_webhook($order_detail->id , 5);
        //             //Orders::update_status_by_customer($order_detail->invoice_number , 'arrived' ,'completed');
        //             DB::commit();
        //         } catch (\Throwable $th) {
        //             //throw $th;
        //             DB::rollBack();
        //             print_r($th->getMessage());
        //         }
        //     }
        //     exit;
        // ProcessDeliveredOrder::dispatch($get_order->order_details_id)->delay(now()->addMinutes(5));
        // print_r($get_order); exit;
        // exit;
        EmailNotificationVendor::dispatch('TRANX53999753' , 'paid'); //email for vendor;
        // exit;
        $transaction = Order::
            with(['user','order_detail','order_detail.shipping','order_detail.vendor','order_detail.vendor.user','order_detail.productswithdetailwithimage','payment','payment.paymentMethod','master_status'])
            ->where('transaction_number' , 'TRANX53999753')->first();
            if($transaction){
            foreach($transaction->order_detail as $item){
                echo $transaction->user->name;
                $name = $item->vendor->user->name;
                $email = $item->vendor->user->email;
                $subject = 'HOTDEAL - PAYMENT ACCEPTED';
                echo str_replace(
                    array("{user_name}" ,"{nomor_invoice}"),
                    array($name, $item->invoice_number),
                    TransactionEmailTemplate::vendor_body_email('paid' , '2022'));

                $data = ['transaction' => $item, 'body' => TransactionEmailTemplate::vendor_body_email('accept' , '2022')];
                // try {
                //     Mail::send('emails.vendor', $data, function ($message) use ($name, $email, $subject) {
                //         $message->to($email, $name)->subject($subject);
                //         $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
                //     });
                // } catch (\Exception $e) {
                //     return $e->getMessage();
                // }

            }
        }
        exit;

        //echo json_encode($transaction);exit;
        $name = $transaction->order->user->name;
        $email = $transaction->order->user->email;
        $subject = TransactionEmailTemplate::$title_accepted;                    
        $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('accept' , '2022-10-11')];
        try {
            Mail::send('emails.update_order', $data, function ($message) use ($name, $email, $subject) {
                $message->to($email, $name)->subject($subject);
                $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
            });
        } catch (\Exception $e) {
            print_r($e->getMessage()); 
        }
        //echo json_encode($transaction);

        exit;
        $name = $transaction->order->user->name;
        $email = $transaction->order->user->email;
        $subject = 'HOTDEAL - ORDER CONFIRMATION';

        
        $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('confirmation' , '2022-10-11')];
        try {
            Mail::send('emails.user_transaction', $data, function ($message) use ($name, $email, $subject) {
                $message->to($email, $name)->subject($subject);
                $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
            });
        } catch (\Exception $e) {
           print_r($e->getMessage()); 
        }

       echo json_encode($transaction); exit;


        $transaction = Order::
        with(['order_detail','order_detail.shipping','order_detail.productswithdetailwithimage' ,'user','payment','payment.paymentMethod','master_status'])
        ->where('transaction_number' , 'TRANX97554898')->first();
        $body['body'] = TransactionEmailTemplate::check_body('confirmation' , '2022-10-11');
        echo json_encode($transaction); exit;

        $name = $transaction->user->name;
        $email = $transaction->user->email;
        $subject = 'HOTDEAL - ORDER CONFIRMATION';
        $data = ['transaction' => $transaction, 'body' => TransactionEmailTemplate::check_body('confirmation' , '2022-10-11')];
        try {
            Mail::send('emails.user_transaction', $data, function ($message) use ($name, $email, $subject) {
                $message->to($email, $name)->subject($subject);
                $message->from('noreply@hotdeal.id', 'Hotdeal Indonesia');
            });
        } catch (\Exception $e) {
           print_r($e->getMessage()); 
        }


        exit;
        TransactionEmailNotification::dispatch('TRANX10257535' , 'create_order');
        exit;
        $transaction = Order::
        with(['order_detail','order_detail.shipping','order_detail.productswithdetail' ,'user','payment','payment.paymentMethod','master_status'])
        ->where('transaction_number' , 'TRANX53999753')->first();
        //echo json_encode($transaction); exit;
        $body['body'] = TransactionEmailTemplate::check_body('confirmation' , '2022-10-11');

        return view('emails.user_transaction' , $transaction , $body);
    }
}
