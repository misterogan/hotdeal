<?php

namespace App\Jobs;

use App\Helpers\Hotpoint;
use App\Helpers\Notify;
use App\Helpers\Utils;
use App\LogVoucherUsage;
use App\MediaLog;
use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\OrderHistory;
use App\ProductDetail;
use App\PromotionVoucher;
use App\TestingJob;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessExpiredOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    // protected $voucher_code;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($details)
    {
        $this->details = $details;
        
        // echo  $this->transaction_number;
        // die();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::where('transaction_number' , $this->details['transaction_number'])->where('status', 1)->first();
        
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
            // $order_product = OrderDetailProduct::where('order_detail_id',$order->id)->get();
            $order_product = OrderDetail::join('order_detail_products', 'order_details.id', 'order_detail_products.order_detail_id')
                                       ->where('order_id',$order->id)->get();
            
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
                $sendPoint = Hotpoint::send($order->user_id , $order->point , 'EFC001' , 'Pengembalian hot point dari transaksi '.$this->details['transaction_number']);
                //MediaLog::create(['log' => 'prepare' , 'created_at' => date('Y-m-d')]);
                if($sendPoint){
                    //MediaLog::create(['log' => 'send' , 'created_at' => date('Y-m-d')]);
                    Notify::send('Hotpoint' , 'Pengembalian hot point dari transaksi '.$this->details['transaction_number'] , '/hotpoint' , '' , 'uid' ,'hotpoint' , $order->user_id);
                }
            }
            // update detail transaction
            OrderDetail::where('order_id' , $order->id)->where('status' , '1')->update([
                'status' => 10,
                'updated_at' => Utils::now()
            ]);
            
            //MediaLog::create(['log' => 'pass' , 'created_at' => date('Y-m-d')]);
            //$user_name = User::select('name')->where('id' , $order->user_id)->first();
            Notify::send('Pesanan Dibatalkan', 
                'Pesanan ' . $order->transaction_number . ' telah di batalkan oleh Hotdeal karena telah melewati batas pembayaran yang ditentukan.',
                '/transactions/pending-transaction',
                '',
                'uid'
                ,'Pembayaran',
                $order->user_id);
            
            // $voucher = PromotionVoucher::where('voucher_code', $this->voucher_code)->where('is_code', true)->uo
            $voucher = PromotionVoucher::where('voucher_code', $this->details['voucher_code'])->where('is_code', true)->first();
            if($voucher){
                $voucher->total += 1;
                $voucher->update();
            }
            
            DB::commit();
        } catch (Throwable $e) {    
            MediaLog::create(['log' => $e->getMessage() , 'created_at' => date('Y-m-d')]);
            DB::rollBack();
        }
    }
}
