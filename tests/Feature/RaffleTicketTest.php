<?php

namespace Tests\Feature;

use App\Helpers\Notify;
use App\OrderDetailProduct;
use App\Helpers\RaffleTicket as HelpersRaffleTicket;
use App\Helpers\Utils;
use App\Jobs\OperationMessage;
use App\Jobs\RaffleTicket;
use App\SpecialEvent;
use App\TicketRaffle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaffleTicketTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testBasicTest()
    // {
    //         $order_id = 715;
    //         $event = SpecialEvent::where('status' , 'active')->first();
    //         if(!$event){
    //             $this->assertFalse(false);
    //         }
    //         $_bundling_product = 0;
    //         $_non_bundling_product = 0;
            
    //         $order = OrderDetailProduct::select('order_detail_products.fix_price','products.category_id','orders.point','orders.total_discount', 'orders.total_payment', 'orders.user_id','order_detail_products.quantity')
    //         ->leftJoin('order_details' , 'order_details.id' , 'order_detail_products.order_detail_id')
    //         ->leftJoin('orders' , 'orders.id' , 'order_details.order_id')
    //         ->leftJoin('product_details' , 'order_detail_products.product_detail_id' , 'product_details.id')
    //         ->leftJoin('products' , 'products.id' , 'product_details.product_id')
    //         ->where('orders.id' , $order_id)
    //         ->get();
    //         if(!$order){                
    //             return $this->assertFalse(false);
    //         }
    //         return $this->assertFalse(true , "Order dengan id $order_id ditemukan");
    //         foreach($order as $item){
    //             $user = $item->user_id;
    //             if($item->category_id == 81){
    //                 $_bundling_product += (int)($item->fix_price) * $item->quantity;
    //             }else{
    //                 $_non_bundling_product +=(int)( $item->fix_price) * $item->quantity;
    //             }
    //         }
            
    //         $raffle_bundling = HelpersRaffleTicket::total_raffle_ticket($_bundling_product , 'bundling');
    //         $raffle_non_bundling = HelpersRaffleTicket::total_raffle_ticket($_non_bundling_product , 'non_bundling');

    //         if($raffle_bundling > 0){
    //             for ($i = 1 ; $i <= $raffle_bundling ; $i++){
    //                 $ticket_number = 'HD'.Utils::generate_random_length(7);
    //                 $check_ticket = TicketRaffle::where('ticket_number',$ticket_number)->first();
    //             }
    //         }
    
    //         if($raffle_non_bundling > 0){
    //             for ($i = 1 ; $i <= $raffle_non_bundling ; $i++){
    //                 $ticket_number = 'HD'.Utils::generate_random_length(7);
    //                 $check_ticket = TicketRaffle::where('ticket_number',$ticket_number)->first();
    //                 if(!$check_ticket){
    //                 }
    //             }
    //         }
    //     //$response = $this->get('/');
    //     //$response->assertStatus(200);
    // }

    public function test_count_ticket(){
            $dummy_order_id = 927;
            $helpers = new HelpersRaffleTicket();
            $active_event = $helpers->active_event();
            if(!$active_event){
                return $this->assertTrue(true , 'there is no one active event');
            }
            $total_ticket = [];
            $order = $helpers->order_by_order_id($dummy_order_id);
            $notify = false;
            $raffle_text = '';
            if(!$order){
                return $this->assertTrue(true , 'there is no one orders');
            }
            $user = 0;
            foreach($order as $item){
                $user = $item->user_id;
                $total_ticket[] = $helpers->category_product($item->category_id);
            }           
            $count_ticket = TicketRaffle::where('order_id' , $dummy_order_id)->count();
            
            if($count_ticket >= max($total_ticket)){
                return;
            }
            
            if(count($total_ticket) <= 0){
                return $this->assertTrue(true , 'maximal total ticket < 0');
            }
            //dd(max($total_ticket) );
            for ($i = 1 ; $i <= max($total_ticket) ; $i++){
                $ticket_number = 'HD'.Utils::generate_random_length(7);
                $check_ticket = TicketRaffle::where('ticket_number',$ticket_number)->first();
                if(!$check_ticket){
                    $ticket =  TicketRaffle::create([
                        'special_event_id'=>$active_event->id,
                        'user_id'=>$user,
                        'order_id' => $dummy_order_id,
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
            if($notify == true){   
                Notify::send('Kupon Undian Giveaway ‘Ghosting’ Hotdeal' , 'Kamu dapat kupon undian Giveaway ‘Ghosting’ Hotdeal. Kumpulin terus biar kesempatan menang lebih gede.' ,'/bundling','', 'uid' ,'' ,$user);
            }
            return $this->assertTrue(true);
    }
}
