<?php

namespace App\Jobs;

use App\OrderDetailProduct;
use App\ProductPurchase;
use App\TestingJob;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TotalPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_product = OrderDetailProduct::select('product_id', 'quantity')
                         ->join('order_details', 'order_detail_products.order_detail_id', '=', 'order_details.id')
                         ->join('product_details', 'order_detail_products.product_detail_id', '=', 'product_details.id')
                         ->where('order_details.invoice_number', $this->invoice)
                         ->groupBy('order_details.id', 'order_detail_products.id', 'product_details.id')
                         ->get();
        foreach ($order_product as $value) {
            $product_purchase = ProductPurchase::where('product_id', $value->product_id)->where('status', 'active')->first();
            if($product_purchase){
                $product_purchase->total = $product_purchase->total + $value->quantity;
                $product_purchase->updated_at = Carbon::now();
                $product_purchase->save();
            } else{
                ProductPurchase::create([
                    'product_id' => $value->product_id,
                    'total' => $value->quantity,
                    'status' => 'active',
                    'created_at' =>Carbon::now(),
                    'updated_at' =>Carbon::now()
                ]);
            }
        }
    }
}
