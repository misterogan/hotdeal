<?php

namespace App\Console\Commands;

use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\OrderHistory;
use App\ProductDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckUnprocessedOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:unprocessed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $unpaid_orders = Order::where('status',3)
            ->where('updated_at', '<=', Carbon::now()->subDays(3)->toDateTimeString())
            ->get();
        foreach ($unpaid_orders as $key => $order){
            $waiting_payment = Order::where('id',$order->id)->update(['status'=>9]);
            if($waiting_payment){
                $order_detail = OrderDetail::where('order_id',$order->id)->get();
                foreach ($order_detail as $key => $od){
                    $order_product = OrderDetailProduct::where('order_detail_id',$od->id)->get();
                    foreach ($order_product as $key => $op){
                        ProductDetail::where('id',$op->product_detail_id)
                            ->update(['stock' => DB::raw('stock+'.$op->quantity)]);
                    }
                }
                OrderHistory::create([
                    'order_id'=>$order->id,
                    'order_status_id'=>9,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
