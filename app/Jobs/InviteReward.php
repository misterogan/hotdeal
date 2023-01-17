<?php

namespace App\Jobs;

use App\Helpers\Hotpoint;
use App\Helpers\InviteRules;
use App\Helpers\Notify;
use App\OrderDetail;
use App\TestingJob;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InviteReward implements ShouldQueue
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
        //
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transaction = OrderDetail::where('invoice_number' , $this->invoice)->with('order')->first();
        $user = User::where('id' , $transaction->order->user_id)->first();
        
        if($user->parent_id != ''){
            $tota_admin_fee = InviteRules::users_reward($this->invoice);
            $total_child = InviteRules::count_of_child($user->parent_id);
            $hotpoint = InviteRules::reward_mechanism($total_child , $tota_admin_fee);
            $has_give_reward = InviteRules::give_reward($transaction->id);
            if(!$has_give_reward){
                return false;
            }
            if($hotpoint < 0){
                return false;
            }
            $send_point = Hotpoint::send($user->parent_id , round($hotpoint) , 'EFI001' , 'Kamu dapat Point dari transaksi invite #'.$this->invoice , $transaction->id);
            if ($send_point) {
                OperationMessage::dispatch(':flying_money: Pesanan dengan nomor transaksi ' .$transaction->order->transaction_number .' mendapat '.$hotpoint.' dari reward invite.');
                return true;
            }
            return false;
        }else{
            return false;
        }
    }
}
