<?php
namespace App\Helpers;

use App\Category;
use App\Jobs\TransactionEmailNotification;
use App\MasterStatusOrder;
use App\OrderDetail;
use App\OrderDetailLog;
use App\TicketRaffle;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Order;
use Illuminate\Support\Facades\Log;

class Orders {

    static function expired_order($type){
        if($type == 'ID_OVO'){
            return now()->addMinutes(1);
        }else if($type == 'ID_LINKAJA' || $type == 'ID_DANA'){
            return now()->addMinutes(30);
        }else if($type == 'VA'){
            return now()->addHours(24);
        }else{
            return now()->addHours(24);
        }
    }

    public static function is_that_you($user_id,$invoice_number){
        $order_details = OrderDetail::leftJoin('orders', function($join){
            $join->on('order_details.order_id','=','orders.id');
        })->where('order_details.invoice_number',$invoice_number)->first([
            'orders.user_id',
        ]);
        if($user_id == $order_details->user_id){
            return true;
        }else{
            return false;
        }

    }

    static function update_status($invoice_number, $vendor_id, $status_code , $reason="") {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $status_id = MasterStatusOrder::where('status_code', $status_code)->pluck('id')->first();

        $order_detail = OrderDetail::where('invoice_number', $invoice_number)->where('vendor_id', $vendor_id)->with('order')->first();

        $vendor = Vendor::where('id', $vendor_id)->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }
        
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            if($status_id == 3){
                $logistic_order = Logistics::logistic_create_order('SHIPPER' , $order_detail->id);
            }
            $order_detail->status = $status_id;
            $order_detail->updated_at = Utils::now();
            if ($order_detail->save()) {
                OrderDetailLog::create([
                    'order_details_id' => $order_detail->id,
                    'status' => $status_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => $vendor->name,
                    'updated_by' => $vendor->name,
                    'description' => $reason
                ]);
                DB::commit();
                Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => '']);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => '']);
            DB::rollback();
            return false;
        }
    }

    static function update_status_by_customer($invoice_number, $status_code, $change_status_to){
        $user = Auth::user();
        if ($user->is_vendor) {
            return false;
        }
        $current_status = MasterStatusOrder::where('status_code', $status_code)->pluck('id')->first();
        $future_status = MasterStatusOrder::where('status_code', $change_status_to)->pluck('id')->first();
        $order_detail = OrderDetail::select('order_details.*')->leftJoin('orders' , 'orders.id' , 'order_details.order_id')
                        ->where('order_details.invoice_number', $invoice_number)
                        ->where('order_details.status', $current_status)
                        ->where('orders.user_id', $user->id)->first();
        if(!$order_detail){
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = $future_status;
            $order_detail->updated_at = Utils::now();
            if ($order_detail->save()) {
                OrderDetailLog::create([
                    'order_details_id' => $order_detail->id,
                    'status' => $future_status,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => $user->name,
                    'updated_by' => $user->name,
                ]);
                DB::commit();
                Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => '']);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => '']);
            DB::rollback();
            return false;
        }
    }

    static function status_to_pending($order_detail_id, $vendor_id) {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $order_detail = OrderDetail::where('id', $order_detail_id)->where('vendor_id', $vendor_id)->with('order')->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = 2;
            $order_detail->updated_at = Carbon::now();
            $order_detail->updated_by = $user->name;
            if ($order_detail->save()) {
                DB::commit();
                //Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => $queries]);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            //Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
    }

    static function status_to_processed($order_detail_id, $vendor_id) {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $order_detail = OrderDetail::where('id', $order_detail_id)->where('vendor_id', $vendor_id)->with('order')->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = 3;
            $order_detail->updated_at = Carbon::now();
            $order_detail->updated_by = $user->name;
            if ($order_detail->save()) {
                DB::commit();
               // Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => $queries]);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
           // Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
    }

    static function status_to_delivery($order_detail_id, $vendor_id) {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $order_detail = OrderDetail::where('id', $order_detail_id)->where('vendor_id', $vendor_id)->with('order')->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = 4;
            $order_detail->updated_at = Carbon::now();
            $order_detail->updated_by = $user->name;
            if ($order_detail->save()) {
                DB::commit();
                //Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => $queries]);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            //Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
    }

    static function status_to_completed($order_detail_id, $vendor_id) {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $order_detail = OrderDetail::where('id', $order_detail_id)->where('vendor_id', $vendor_id)->with('order')->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = 5;
            $order_detail->updated_at = Carbon::now();
            $order_detail->updated_by = $user->name;
            if ($order_detail->save()) {
                DB::commit();
                //Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => $queries]);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
           // Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
    }

    static function status_to_refunded($order_detail_id, $vendor_id) {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $order_detail = OrderDetail::where('id', $order_detail_id)->where('vendor_id', $vendor_id)->with('order')->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = 7;
            $order_detail->updated_at = Carbon::now();
            $order_detail->updated_by = $user->name;
            if ($order_detail->save()) {
                DB::commit();
                //Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => $queries]);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            //Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
    }

    static function status_to_vendor_canceled($order_detail_id, $vendor_id) {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return false;
        }

        $order_detail = OrderDetail::where('id', $order_detail_id)->where('vendor_id', $vendor_id)->with('order')->first();

        if ($order_detail == null || $vendor_id != $order_detail->vendor_id) {
            return false;
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $order_detail->status = 9;
            $order_detail->updated_at = Carbon::now();
            $order_detail->updated_by = $user->name;
            if ($order_detail->save()) {
                DB::commit();
                 //Log::channel('errorlog')->info(['module'=> 'Order', 'function' => 'update', 'class'=>'OrderController' ,'params' => '' , 'query' => $queries]);
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            //Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
    }

    public static function update_status_detail_by_order_id($order_id , $status_id){
        $detail = OrderDetail::select('id')->where('order_id' , $order_id)->get();
        $data = [];
        if($detail){
            $update_status_detail = OrderDetail::where('order_id', $order_id)
            ->update([
                'status'=> $status_id
            ]);
            if($update_status_detail){
                foreach($detail as $item){
                   self::save_detail_log([
                        'order_details_id' => $item->id,
                        'status' => $status_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

        }

    }

    public static function update_status_detail_complete_by_order_id($order_id , $status_id){
        $detail = OrderDetail::select('id')->where('order_id' , $order_id)->get();
        $data = [];
        if($detail){
            $update_status_detail = OrderDetail::where('order_id', $order_id)
            ->update([
                'status'=> $status_id
            ]);
            if($update_status_detail){
                foreach($detail as $item){
                   self::save_detail_log([
                        'order_details_id' => $item->id,
                        'status' => $status_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

        }

    }

    public static function save_detail_log($data){
        OrderDetailLog::create($data);
    }

    public static function update_status_from_webhook($order_detail_id , $status){
        $order = OrderDetail::where('id' , $order_detail_id)->first();
        if($order->status == 14){
            $order->status = $status;
            $order->save();
            OrderDetail::where('id' , $order_detail_id)->update([
                'status' => $status
            ]);
            self::save_detail_log([
                'order_details_id' => $order_detail_id,
                'status' => $status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }


        if($order->status == 4 && $status == 12){
            $order->status = $status;
            $order->save();
            OrderDetail::where('id' , $order_detail_id)->update([
                'status' => $status
            ]);
            self::save_detail_log([
                'order_details_id' => $order_detail_id,
                'status' => $status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if($order->status == 12 && $status == 5){
            $order->status = $status;
            $order->save();
            OrderDetail::where('id' , $order_detail_id)->update([
                'status' => $status
            ]);
            self::save_detail_log([
                'order_details_id' => $order_detail_id,
                'status' => $status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    public static function receive_ticket($category_id,$total_payment,$uid){

        $category = Category::where('id',$category_id)->first();
        $total_raffle = 1;
        if($category->role == 'product_bundling'){
            if($total_payment >= 100000){
                $total_raffle = 3;
            }
            elseif($total_payment >= 300000) {
                $total_raffle = 5;
            }
            elseif($total_payment >= 500000){
                $total_raffle = 10;
            }
        }
        //insert to table

        for ($i = 1 ; $i < $total_raffle ; $i++){
            $ticket_number = 'HD'.Utils::generate_random_length(7);
            $check_ticket = TicketRaffle::get('ticket_number',$ticket_number)->first();
            if(!$check_ticket){
                $ticket =  TicketRaffle::create([
                    'special_event_id'=>'',
                    'user_id'=>$uid,
                    'ticket_number'=>$ticket_number,
                    'created_by'=>'sistem',
                    'status'=>'active',
                    'is_winner'=>false,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s'),
                ]);
            }

        }

    }

}
