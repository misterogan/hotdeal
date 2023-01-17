<?php

namespace App\Console\Commands;

use App\Helpers\RejekiNomplok;
use App\Http\Controllers\Api\TransactionController;
use App\OrderDetail;
use App\OrderDetailLog;
use App\TestingJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompletedOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command complete order';

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
        $date = Carbon::now()->subDays(3);

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            
            $detail = OrderDetail::select('id', 'status')->where('status', 12)
                ->where(DB::raw('DATE(updated_at)'), '<=', $date->toDateString())->get();
                
                foreach ($detail as $log) {
                $order = OrderDetail::where('id', $log->id)->with('order')->first();
                $coupon = RejekiNomplok::create_coupon($order->invoice_number, $order->order->user_id);
                if ($order) {
                    $order->status = 5;
                    $order->updated_at = Carbon::now();
                    $order->save();

                    OrderDetailLog::create([
                        'order_details_id' => $log->id,
                        'status' => 5,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => 'System'
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => '']);
            DB::rollback();
            return false;
        }

        return true;
    }
}
