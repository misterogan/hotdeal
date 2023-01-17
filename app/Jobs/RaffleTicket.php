<?php

namespace App\Jobs;

use App\Helpers\Notify;
use App\Helpers\RaffleTicket as HelpersRaffleTicket;
use App\Helpers\Utils;
use App\MediaLog;
use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\RejekiNomplokCoupon;
use App\SpecialEvent;
use App\TicketRaffle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RaffleTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        //
        $this->order_id = $order_id;        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Process Raffle Ticket
        try {
            $helpers = new HelpersRaffleTicket();
            $active_event = $helpers->active_event();
            if(!$active_event){
                return;
            }
            $total_ticket = [];
            $order = $helpers->order_by_order_id($this->order_id);
            $notify = false;
            $raffle_text = '';
            if(!$order){
                return ;
            }
            $user = 0;
            foreach($order as $item){
                $user = $item->user_id;
                $total_ticket[] = $helpers->category_product($item->category_id);
            }           
            if(count($total_ticket) <= 0){
                return ;
            }
            $count_ticket = TicketRaffle::where('order_id' , $this->order_id)->count();
            
            //dd($count_ticket);

            if($count_ticket >= max($total_ticket)){
                return;
            }
            for ($i = 1 ; $i <= max($total_ticket) ; $i++){
                $ticket_number = 'HD'.Utils::generate_random_length(7);
                $check_ticket = TicketRaffle::where('ticket_number',$ticket_number)->first();
                if(!$check_ticket){
                    $ticket =  TicketRaffle::create([
                        'special_event_id'=>$active_event->id,
                        'user_id'=>$user,
                        'order_id' => $this->order_id,
                        'ticket_number'=>$ticket_number,
                        'created_by'=>'sistem',
                        'status'=>'active',
                        'is_winner'=>false,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ]);
                    if($ticket){
                        $notify = true;
                    }
                }
            }
            OperationMessage::dispatch(':ticket: Order dengan nomor transaksi '.$order[0]->transaction_number.' Mendapatkan *'.max($total_ticket). 'tiket* ' );
        } catch (\Throwable $e) {
            MediaLog::create(['log' => $e->getMessage()]);
        }
        if($notify == true){   
            Notify::send('Kupon Undian Giveaway ‘Ghosting’ Hotdeal' , 'Kamu dapat kupon undian Giveaway ‘Ghosting’ Hotdeal. Kumpulin terus biar kesempatan menang lebih gede.' ,'/bundling','', 'uid' ,'' ,$user);
        }
    }
}
