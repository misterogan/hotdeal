<?php

namespace App\Http\Controllers\Api\Payment;

use App\CallbackPayment;
use App\Helpers\Emails;
use App\Helpers\Notify;
use App\Helpers\OrderDetails;
use App\Helpers\Orders;
use App\Helpers\Payment;
use App\Helpers\Utils;
use App\Http\Controllers\Api\Api;
use App\Jobs\CancelOrderAfterPaid;
use App\Jobs\EmailNotificationVendor;
use App\Jobs\OperationMessage;
use App\Jobs\RaffleTicket;
use App\Jobs\TransactionEmailNotification;
use App\Jobs\TransactionNotification;
use App\Order;
use App\OrderDetail;
use App\OrderLog;
use App\OrderPayment;
use App\OrderPaymentLog;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CallbackController extends Api
{
    /**
     * @OA\Post(
     * path="/api/payment/callback/hotdeal",
     * summary="get",
     * description="message get",
     * operationId="messageget",
     * tags={"User"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

     public function callback(Request $request){
         
        $callback_source_id = $this->callback_source($request->all() , 'BANK');
        
        if(!$this->validate_header_request($request->header('x-callback-token'))){
            
            $this->save_request_data($callback_source_id['id'] , $request->all() , 'HEADER API NOT VALID');
            return $this->errorResponse('HEADER API NOT VALID',static::PAYMENT_FAILED_CODE);;

        }
        $this->save_request_data($callback_source_id['id'] , $request->all() , 'XXX');
        
        if(!$callback_source_id){
             $this->save_request_data($callback_source_id['id'] , $request->all() , 'Not Declare');
             return $this->errorResponse('Not Declare',static::PAYMENT_FAILED_CODE);;
        }
        
        $external_id = $callback_source_id['id'];
        
        $order_payment = OrderPayment::where('external_id',$external_id)->first();

        if($order_payment) {
            // call api xendit if status has SUCCEEDED ( PAID )
            if(!isset($request->payment_id)){
                $this->save_request_data($external_id , $request->all() , 'PAYMENT_ID NOT EXIST');
                return $this->errorResponse('PAYMENT STILL PENDING WHEN CHECK' ,static::PAYMENT_FAILED_CODE); 
            }
            if(!Payment::VirtualAccountCheckPayment($request->payment_id , $external_id , $order_payment->expected_amount , $order_payment->merchant_code)){
                $this->save_request_data($external_id , $request->all() , 'PAYMENT_ID IS FAILED');
                return $this->errorResponse('PAYMENT_ID IS FAILED' ,static::PAYMENT_FAILED_CODE);
            }

            // SAVE LOG OF CALLBACK
            $this->save_request_data($external_id , $request->all() , $callback_source_id['payment_gate']);
            
            $order_id = $order_payment->order_id;
            // UPDATE ORDER STATUS
            $order = Order::where('id',$order_id)->where('status' , 1)->first();
            
            if($order){
                try {
                    DB::beginTransaction();
                    $order->status = 2;
                    if($order->save()){
                        $name = User::where('id' , $order->user_id)->first();
                        // CREATE LOG PAYMENT
                        $order_log = OrderLog::create([
                            'order_id'=>$order_id,
                            'status_id'=>2,
                            'created_at'=>date('Y-m-d H:i:s')
                        ]);
                        // UPDATE DETAIL ORDER (INVOICE) STATUS
                        $order_details_ = Orders::update_status_detail_by_order_id($order_id , 2);
                        // CREATE ORDER PAYMENT LOG
                        $order_payment_log = OrderPaymentLog::create([
                            'external_id'=>$external_id,
                            'order_id'=>$order_payment->order_id,
                            'payload'=>'',
                            'response'=>json_encode($request->all()),
                            'created_at'=>date('Y-m-d H:i:s')
                        ]);

                        $vendor_array = OrderDetail::select('vendor_id')->where('order_id', $order_payment->order_id)->get()->toArray();
                        // CREATE NOTIFICATION
                        if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                        array("{name}", "{transaction_number}"),
                        array($name->name, $order->transaction_number),
                        Notify::$PAYMENT_SUCCESS_BODY), Notify::$PAYMENT_SUCCESS_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , $name->id, Notify::$PAYMENT_SEND_BY)){
                            return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                        }
                        // ADMIN NOTIFICATION
                        if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                        array("{name}", "{transaction_number}"),
                        array('Admin', $order->transaction_number),
                        Notify::$PAYMENT_SUCCESS_BODY_ADMIN), '/admin/order' , '', 'uid' , Notify::$PAYMENT_TOPIC , '0', Notify::$PAYMENT_SEND_BY)){
                            return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                        }
                        //VENDOR NOTIFICATION
                        foreach ($vendor_array as $key => $vendor) {
                            $vendor_data = Vendor::where('id', $vendor['vendor_id'])->first();
                            if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                                array("{name}", "{transaction_number}"),
                                array($vendor_data->name, $order->transaction_number),
                                Notify::$VENDOR_PAYMENT_SUCCESS_BODY), Notify::$PAYMENT_SUCCESS_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , $vendor_data->user_id, Notify::$PAYMENT_SEND_BY)){
                                    return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                                } 
                        }
                        //VENDOR EMAIL
                        foreach ($vendor_array as $key => $vendor) {
                            $vendor_data = Vendor::where('id', $vendor['vendor_id'])->first();
                            Emails::send_email($vendor_data->name, $vendor_data->user->email, 'Order Payment', $order->transaction_number, Emails::$VENDOR_PAYMENT);
                        }

                        $order_payment->status = 'SUCCEEDED';
                        $order_payment->save();
                        DB::commit();
                        TransactionEmailNotification::dispatch($order->transaction_number , 'paid');
                        RaffleTicket::dispatch($order_id);
                        OperationMessage::dispatch(':flying_money: Pesanan dengan nomor transaksi ' .$order->transaction_number .' telah melakukan pembayaran.');
                        //CancelOrderAfterPaid::dispatch($order->id)->delay(now()->addDays(1));
                        return $this->successResponse('SUCCEEDED',static::PAYMENT_SUCCESS_CODE);
                    }else{
                        DB::rollBack();
                        return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                    }

                } catch (Throwable $e) {
                    DB::rollBack();
                    return  $this->errorResponse(static::ORDER_ERROR_SAVE_DATA, $e->getMessage());
                }
                    
            }
            return $this->successResponse('SUDAH DIUPDATE',static::PAYMENT_SUCCESS_CODE);
        }
        return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
     }

     public function callback_ewallet(Request $request){
        $payload = $request->all();
        $callback_source_id = $this->callback_source($payload, 'EWALLET');
        
        if(!$this->validate_header_request($request->header('x-callback-token'))){
            $this->save_request_data($callback_source_id['id'] , $payload , 'HEADER API NOT VALID');
            return  $this->errorResponse('HEADER API NOT VALID' ,static::PAYMENT_FAILED_CODE);
        }

        if(!$callback_source_id){
            $this->save_request_data($callback_source_id['id'] , $payload , 'UNKNOWN RESOURCE');
            return $this->errorResponse('UNKNOWN RESOURCE' ,static::PAYMENT_FAILED_CODE);
            
        }
        
        $external_id = $callback_source_id['id'];
        
        $order_payment = OrderPayment::where('external_id',$external_id)->first();

        if($order_payment) {
            
            // call api xendit if status has SUCCEEDED ( PAID )
            if(!Payment::checkEwalletPaymentStatus($order_payment->id_payment)){
                 $this->save_request_data($external_id , $payload , 'PAYMENT STILL PENDING WHEN CHECK');
                 return $this->errorResponse('PAYMENT STILL PENDING WHEN CHECK' ,static::PAYMENT_FAILED_CODE);
            }
             // SAVE LOG OF CALLBACK

            $this->save_request_data($external_id , $payload , $callback_source_id['payment_gate']);

            $order_id = $order_payment->order_id;
            // UPDATE ORDER STATUS
            $order = Order::where('id',$order_id)->where('status' , 1)->first();

            if($order){
                try {
                    DB::beginTransaction();
                    $order->status = 2;
                    if($order->save()){
                        $name = User::where('id' , $order->user_id)->first();
                        // CREATE LOG PAYMENT
                        $order_log = OrderLog::create([
                            'order_id'=>$order_id,
                            'status_id'=>2,
                            'created_at'=>date('Y-m-d H:i:s')
                        ]);
                        // UPDATE DETAIL ORDER (INVOICE) STATUS
                        $order_details_ = Orders::update_status_detail_by_order_id($order_id , 2);
                        // CREATE ORDER PAYMENT LOG
                        $order_payment_log = OrderPaymentLog::create([
                            'external_id'=>$external_id,
                            'order_id'=>$order_payment->order_id,
                            'payload'=>'',
                            'response'=>json_encode($payload),
                            'created_at'=>date('Y-m-d H:i:s')
                        ]);
                        // CREATE NOTIFICATION
                        if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                        array("{name}", "{transaction_number}"),
                        array($name->name, $order->transaction_number),
                        Notify::$PAYMENT_SUCCESS_BODY), Notify::$PAYMENT_SUCCESS_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , $name->id, Notify::$PAYMENT_SEND_BY)){
                            return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                        }

                        // ADMIN NOTIFICATION
                        if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                            array("{name}", "{transaction_number}"),
                            array('Admin', $order->transaction_number),
                            Notify::$PAYMENT_SUCCESS_BODY_ADMIN), '/admin/order' , '', 'uid' , Notify::$PAYMENT_TOPIC , '0', Notify::$PAYMENT_SEND_BY)){
                                return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                            }
                        
                        //VENDOR NOTIFICATION
                        $vendor_array = OrderDetail::select('vendor_id')->where('order_id', $order_payment->order_id)->get()->toArray();
                        foreach ($vendor_array as $key => $vendor) {
                            $vendor_data = Vendor::where('id', $vendor['vendor_id'])->first();
                            if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                                array("{name}", "{transaction_number}"),
                                array($vendor_data->name, $order->transaction_number),
                                Notify::$VENDOR_PAYMENT_SUCCESS_BODY), Notify::$PAYMENT_SUCCESS_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , $vendor_data->user_id, Notify::$PAYMENT_SEND_BY)){
                                    return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                                } 
                        }

                        $order_payment->status = 'SUCCEEDED';
                        $order_payment->save();
                        DB::commit();
                        TransactionEmailNotification::dispatch($order->transaction_number , 'paid');
                        //CancelOrderAfterPaid::dispatch($order->id)->delay(now()->addDays(1));
                        EmailNotificationVendor::dispatch($order->transaction_number , 'accept'); //email for vendor
                        RaffleTicket::dispatch($order_id);
                        return $this->successResponse('SUCCEEDED',static::PAYMENT_SUCCESS_CODE);

                    }else{
                        DB::rollBack();
                        return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                    }
                    
                } catch (Throwable $e) {
                    DB::rollBack();
                    // Dont miss save the logs
                    return  $this->errorResponse(static::ORDER_ERROR_SAVE_DATA, $e->getMessage());
                }
            }
            return $this->successResponse('SUDAH DIUPDATE' ,static::PAYMENT_SUCCESS_CODE);
        }
        return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
     }

     public function va_created_callback(Request $request){
        if(!$this->validate_header_request($request->header('x-callback-token'))){
            $this->save_request_data($request->external_id , $request->all() , 'HEADER API NOT VALID');
            return  $this->errorResponse('HEADER API NOT VALID' ,static::PAYMENT_FAILED_CODE);
        }     
        $order_payment = OrderPayment::where('external_id',$request->external_id)->first();
        if($order_payment) {
            $order_payment->status = $request->status;
            $order_payment->updated_at = Utils::now();
            $order_payment->save();
        }
        $this->save_request_data($request->external_id , $request->all() , 'XENDIT');
        return $this->successResponse();
     }

     public function callback_source($body , $channel){
       
        if($channel == 'EWALLET'){
            return ['id' => $body['data']['reference_id'] , 'payment_gate' => 'XENDIT'];
        }else{
            if(array_key_exists('external_id' , $body)){
                return ['id' => $body['external_id'] , 'payment_gate' => 'XENDIT'];
             }
             if(array_key_exists('transaction_id' , $body)){
                return ['id' => $body['transaction_id'] , 'payment_gate' => 'OTHERS'];
             }
        }
         return false;
     }

     public function save_request_data($external_id , $request_data , $payment_gate){
        CallbackPayment::create([
            'external_id'=>$external_id,
            'log'=> json_encode($request_data),
            'payment_gate'=> $payment_gate,
        ]);
     }

     public function validate_header_request($header){
        $token = config('app.env') == 'DEVELOPMENT' ? Payment::$HEADER_TOKEN_SANDBOX : Payment::$HEADER_TOKEN;
        if($header == $token){
            return true;
        }
        return false;
     }

}
