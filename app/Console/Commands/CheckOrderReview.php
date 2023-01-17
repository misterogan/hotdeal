<?php

namespace App\Console\Commands;

use App\OrderDetail;
use App\OrderDetailLog;
use App\Review;
use App\ReviewGallery;
use App\TestingJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckOrderReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create order review';

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
        $date = Carbon::now()->subDays(5);
        $order_log = OrderDetail::select(
                            'user_id','order_details.id as order_details_id','product_id', 'order_details.created_at'
                            )
                        ->leftJoin('orders','orders.id','=','order_details.order_id')
                        ->leftJoin('order_detail_products','order_detail_products.order_detail_id','=','order_details.id')
                        ->leftJoin('product_details','product_details.id','=','product_detail_id')
                        ->where(DB::raw('DATE(order_details.created_at)'), '<=', $date->toDateString())
                        ->where('order_details.status', 5)
                        ->get();

        foreach ($order_log as $log) {
            $review = Review::where('status', 'active')->where('order_details_id', $log->order_details_id)->where('user_id', $log->user_id)->first();
            if(!$review){
                if($log->product_id != null){
                    $rev = Review::create([
                        'rating' => 5,
                        'order_details_id' => $log->order_details_id,
                        'product_id' => $log->product_id,
                        'user_id' => $log->user_id,
                        'status' => 'active',
                        'review' => '',
                        'created_at' => $log->created_at,
                        'updated_at' => $log->created_at
                    ]);

                    if($rev){
                        ReviewGallery::create([
                            'review_id' => $rev->id,
                            'type' => 'image',
                            'url_source' => '',
                            'status' => 'active',
                        ]);
                        ReviewGallery::create([
                            'review_id' => $rev->id,
                            'type' => 'video',
                            'url_source' => '',
                            'status' => 'active',
                        ]);
                    }
                }
            }
        }
    }
}
