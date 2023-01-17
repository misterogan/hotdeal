<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Emails;
use App\Helpers\Hotpoint as HelpersHotpoint;
use App\Helpers\Notify;
use App\Helpers\Orders;
use App\Helpers\Utils;
use App\HighlightProduct;
use App\Hotpoint;
use App\HotpointCode;
use App\HotpointSendLog;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessExpiredOrder;
use App\MediaLog;
use App\Order;
use App\OrderDetailProduct;
use App\OrderHistory;
use App\OtpCode;
use App\ProductDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class HotpointController extends Controller
{
    public function index(Request $request) {
        return view('admin.hotpoint.index');
    }

    public function point(Request $request) {
        return view('admin.hotpoint.send');
    }

    public function send_otp_point(){
        // send otp to cashtree's email
        $send_to = 'agust@cashtree.id';

        $otp = Emails::otp_number();
        $email = Emails::send_email($send_to, $send_to, 'Send Hotpoint Confirmation', $otp, Emails::$SEND_HOTPOINT);
        if($email) {
            $otp = OtpCode::create([
                'email'=> $send_to,
                'code'=> $otp,
                'requested_at'=>Carbon::now(),
                'expired_at'=> Carbon::now()->addMinutes(5),
                'created_at'=> Carbon::now(),
            ]);
            return json_encode(['status' => true , 'msg' => 'request otp succeed']);
        } else{
            return json_encode(['status' => false , 'msg' => 'request otp failed!']);
        }
    }

    public function send_point(Request $request) {
        $validation = Validator::make($request->all(), [
            'target' => 'required',
            'amount' => 'required|numeric',
            'emails' => 'required',
            'otp'   => 'required',
        ]);
        if ($validation->fails()) {
            return json_encode(['status' => false , 'message' => $validation->errors()]);
        }
        $emails = explode(',' , $request->emails);
        $msg = 'Point Terkirim ';
        $status = true;
        $otp = $request->otp;
        $check_otp = OtpCode::where('code', $otp)->where('expired_at', '>', Carbon::now())->first();
        if($check_otp){
            if(count($emails) > 0){
                foreach($emails as $email){
                    // check email
                    $user_id = User::select('id')->where('email' , strtolower(trim($email)))->where('status' , 'active')->first();
                    if($user_id){

                        if($request->target == 'cashtree_member'){
                            // check if has send for this month
                            $history = Hotpoint::where('user_id' , $user_id->id)->where('code' , 'EFM001')->where(DB::raw("to_char(created_at, 'YYYY-MM')"), date('Y-m'))->first();

                            $sendPoint = HelpersHotpoint::send($user_id->id , $request->amount , 'EFM001' , $request->description);
                            if(!$sendPoint){
                                $msg = $email.' Gagal mengirim hotpoint ,';
                                $status = false;
                            }else{
                                HotpointSendLog::create([
                                    'amount'        =>$request->amount,
                                    'email'         =>$email,
                                    'description'   =>$request->description,
                                    'created_by'    =>Auth::user()->name,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                ]);
                                Notify::send('Hotpoint' , $request->description , '/hotpoint' , '' , 'uid' ,'hotpoint' , $user_id->id);
                            }
                            
                        }else{
                            $sendPoint = HelpersHotpoint::send($user_id->id , $request->amount , 'EFE001' , $request->description);
                            if(!$sendPoint){
                                $msg = $email.' Gagal mengirim hotpoint ,';
                                $status = false;
                            } else {
                                HotpointSendLog::create([
                                    'amount'        =>$request->amount,
                                    'email'         =>$email,
                                    'description'   =>$request->description,
                                    'created_by'    =>Auth::user()->name,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                ]);
                                Notify::send('Hotpoint' , $request->description , '/hotpoint' , '' , 'uid' ,'hotpoint' , $user_id->id);
                            }
                        }
                    }else{
                        $status = false;
                        $msg .= $email .' tidak terdaftar ,';
                    }
                }
            }
        } else{
            return json_encode(['status' => false , 'message' => ['OTP Expired!']]);
        }
        return json_encode(['status' => $status , 'message' => $msg]);
    }

    public function send_point_event(Request $request) {
        // $validation = Validator::make($request->all(), [
        //     'emails' => 'required',
        // ]);
        // if ($validation->fails()) {
        //     return json_encode(['status' => false , 'message' => $validation->errors()->toArray()['email'][0]]);
        // }
        $email_data ='';// 'rizty999@gmail.com,avidacantik123@gmail.com,muharidexecutive@gmail.com,roesdex@gmail.com';
        $emails = explode(',' , $email_data);
        $msg = '';
        if(count($emails) > 0){
            foreach($emails as $email){
                // check email
                $user_id = User::select('id')->where('email' , strtolower(trim($email)))->where('status' , 'active')->first();
                if($user_id){
                    // check if has send for this month
                    $history = Hotpoint::where('user_id' , $user_id->id)->where('code' , 'EFE002')->where(DB::raw("to_char(created_at, 'YYYY-MM')"), date('Y-m'))->first();
                    if($history){
                        $msg .=  $email.' bulan ini sudah dikirm ,';
                    }else{
                        $sendPoint = HelpersHotpoint::send($user_id->id , $request->amount , 'EFE002' , 'Selamat kamu dapat Hot Point sebesar 50.000 atas partisipasimu mengisi Survei Online Hotdeal');
                        if(!$sendPoint){
                            $msg .= $email.' Gagal dikirim hotpoint ,';
                        }else{
                            Notify::send('Pemenang Hotdeal Idea Batch 1' , 'Selamat kamu dapat Hot Point sebesar 100.000 atas partisipasimu memberikan ide produk.' , '/hot-point' , '' , 'uid' ,'hotpoint' , $user_id->id);
                        }
                    }
                }else{
                    $msg .= $email .' tidak terdaftar ,';
                }
            }
        }
        return json_encode(['status' => true , 'msg' => $msg]);
    }

    public function send_point_from_cancel_order(){
        $transaction_number = 'TRANX10199545';
        //$a= ProcessExpiredOrder::dispatch($transaction_number)->delay(Orders::expired_order('ID_OVO'));
        exit;
        $order = Order::where('transaction_number' , $transaction_number)->where('status', 2)->first();
        //print_r($order); exit;
        MediaLog::create(['log' => 'aa' , 'created_at' => date('Y-m-d')]);

        if(!$order){
            return false;
        }
        $order->status = 10;
        try {
            DB::beginTransaction();
            // Update status order
            $order->save();
            // MediaLog::create(['log' => 'aaa' , 'created_at' => date('Y-m-d')]);
            $order_product = OrderDetailProduct::where('order_detail_id',$order->id)->get();

            foreach ($order_product as $key => $op){
                // update stock
                ProductDetail::where('id',$op->product_detail_id)->update(['stock' => DB::raw('stock+'.$op->quantity)]);
            }
            OrderHistory::create([
                'order_id'=>$order->id,
                'order_status_id'=>10,
                'created_at'=>date('Y-m-d H:i:s'),
            ]);

            // Return Point
            //MediaLog::create(['log' => 'start' , 'created_at' => date('Y-m-d')]);
            if($order->point > 0){
                $sendPoint = HelpersHotpoint::send($order->user_id , ($order->point + $order->total_payment) , 'EFC001' , 'Pengembalian hot point dari transaksi '.$transaction_number);
                //MediaLog::create(['log' => 'prepare' , 'created_at' => date('Y-m-d')]);
                if($sendPoint){
                    //MediaLog::create(['log' => 'send' , 'created_at' => date('Y-m-d')]);
                    Notify::send('Hotpoint' , 'Pengembalian hot point dari transaksi '.$transaction_number , '/hotpoint' , '' , 'uid' ,'hotpoint' , $order->user_id);
                }
            }
            //MediaLog::create(['log' => 'pass' , 'created_at' => date('Y-m-d')]);
            //$user_name = User::select('name')->where('id' , $order->user_id)->first();
            Notify::send('Pesanan Dibatalkan',
                         'Pesanan ' . $order->transaction_number . ' telah di batalkan oleh Penjual.',
                         '/transactions/pending-transaction',
                          '',
                          'uid'
                          ,'Pembayaran',
                           $order->user_id);
            DB::commit();
        } catch (Throwable $e) {
            print_r($e->getMessage());
            MediaLog::create(['log' => $e->getMessage() , 'created_at' => date('Y-m-d')]);
            DB::rollBack();
        }


        // MediaLog::create(['log' => 'send' , 'created_at' => date('Y-m-d')]);

        // $transaction_number = 'TRANX10048545';

        // $a= ProcessExpiredOrder::dispatch($transaction_number)->delay(Orders::expired_order('ID_OVO'));
        // print_r($a);
        // exit;
        // $order = Order::where('transaction_number' , $transaction_number)->where('status', 1)->first();
        // if(!$order){
        //     return false;
        // }
        // $order->status = 10;
        // try {
        //     DB::beginTransaction();
        //     // Update status order
        //     $order->save();

        //     $order_product = OrderDetailProduct::where('order_detail_id',$order->id)->get();

        //     foreach ($order_product as $key => $op){
        //         // update stock
        //         ProductDetail::where('id',$op->product_detail_id)->update(['stock' => DB::raw('stock+'.$op->quantity)]);
        //     }
        //     OrderHistory::create([
        //         'order_id'=>$order->id,
        //         'order_status_id'=>10,
        //         'created_at'=>date('Y-m-d H:i:s'),
        //     ]);

        //     // Return Point
        //     if($order->point > 0){
        //         $sendPoint = HelpersHotpoint::send($order->user_id , $order->point , 'EFC001' , 'Pengembalian hot point dari transaksi '. $transaction_number);
        //         if($sendPoint){
        //             Notify::send('Hotpoint' , 'Pengembalian hot point dari transaksi '.$transaction_number, '/transactions/pending-transaction' , '' , 'uid' ,'hotpoint' , $order->user_id);
        //         }
        //     }
        //     $user_name = User::select('name')->where('id' , $order->user_id)->first();
        //     Notify::send('Pesanan Dibatalkan',
        //                  'Pesanan ' . $order->transaction_number . ' telah di batalkan oleh Hotdeal karena telah melewati batas pembayaran yang ditentukan.',
        //                  '/transactions/pending-transaction',
        //                   '',
        //                   'uid'
        //                   ,'Pembayaran',
        //                    $order->user_id);
        //     DB::commit();
        // } catch (Throwable $e) {
        //     print_r($e->getMessage());
        //     DB::rollBack();
        // }

        // exit;

        // $orders = Order::whereIn('status' , [10])->where('point' ,'>', 0)->get();

        // foreach($orders as $order){
        //     if($order->point > 0 ){
        //         //print_r($order);
        //         // $sendPoint = HelpersHotpoint::send($order->user_id , $order->point , 'EFC001' , 'Pengembalian hot point dari transaksi '.$order->transaction_number);
        //         // if($sendPoint){
        //         //     Notify::send('Hotpoint' , 'Pengembalian hot point dari transaksi '.$order->transaction_number , '/transactions/pending-transaction' , '' , 'uid' ,'hotpoint' , $order->user_id);
        //         // }
        //     }
        // }

    }

    public function code_view(){
        return view('admin.hotpoint.list_code');
    }

    public function code_create(){
        return view('admin.hotpoint.create_code');
    }

    public function code_submit(Request $request){

        $validation = Validator::make($request->all(), [
            'code'          => 'required|unique:hotpoint_codes',
            'description'   => 'required',
            'status'        => 'required',
        ]);
        if ($validation->fails()) {
            return json_encode(['status' => false , 'message' => $validation->errors()]);
        }

        $hotpoint_code = HotpointCode::create([
           'code'           => $request->code,
           'description'    => $request->description,
           'status'         => $request->status,
        ]);

        $status = true;
        $msg = "Hotpoint Code successfully created";
        return json_encode(['status' => $status , 'message' => $msg]);
    }

    public function hotpoint_dt() {
        return DataTables::of(HotpointCode::get())->addIndexColumn()->make(true);
    }

    public function get_data_send() {
        return DataTables::of(HotpointSendLog::get())->addIndexColumn()->make(true);
    }
    public function edit_view(Request $request) {
        $hotpoint_code =  HotpointCode::where('id',$request->id)->first();
        return view('admin.hotpoint.edit_code',compact('hotpoint_code'));
    }

    public function update(Request $request){

        $validation = Validator::make($request->all(), [
            'code'          =>  'required',
            'description'   => 'required',
            'status'        => 'required',
        ]);
        if ($validation->fails()) {
            return json_encode(['status' => false , 'message' => $validation->errors()]);
        }
        $hotpoint =  HotpointCode::where('id',$request->code_id)->first();

        $hotpoint->code = $request->code;
        $hotpoint->description = $request->description;
        $hotpoint->status = $request->status;
        $hotpoint->updated_at = date('Y-m-d H:i:s');
        $hotpoint->save();

        $status = true;
        $msg = "Hotpoint Code successfully edited";
        return json_encode(['status' => $status , 'message' => $msg]);

    }



}
