<?php

namespace Tests\Feature;

use App\Helpers\Hotpoint;
use App\Helpers\InviteRules;
use App\Http\Controllers\Api\InviteController;
use App\Jobs\InviteReward;
use App\OrderDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InviteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
       
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_invoice(){


        $invoice =  'INV/220912/HDID/0003';
        // $transaction = OrderDetail::where('invoice_number' , $invoice)->with('order')->first();
        $transaction = OrderDetail::where('invoice_number', $invoice)->with('order_products')->first();
        // dd($transaction);
        $user = User::where('id' , $transaction->order->user_id)->first();
        //dd($user);

        // if(!Hotpoint::send($user->parent_id , 100 , 'EFI001' , 'Kamu dapat Point dari transaksi invite #'.$invoice)){
        //     dd('errr');
        //     // Log::critical("Hi Team , I found the error :( " , [
        //     //     'url' => '',
        //     //     'exception_message' => json_encode($user),
        //     //     'user-agent' => ''
        //     // ]);
        // }


        if($user->parent_id != ''){

            $tota_admin_fee = InviteRules::users_reward($invoice);
            $total_child = InviteRules::count_of_child($user->parent_id);
            $hotpoint = InviteRules::reward_mechanism($total_child , $tota_admin_fee);
            Hotpoint::send($user->parent_id , $hotpoint , 'EFI001' , 'Kamu dapat Point dari transaksi invite #'.$invoice);
        }else{
            $this->assertTrue(false);
        }
        //Log::channel('notice')->notice(':flying_money: Pesanan dengan nomor transaksi ' .$transaction->order->transaction_number .' mendapat'.$hotpoint.' dari reward invite.');
        //OperationMessage::dispatch(':flying_money: Pesanan dengan nomor transaksi ' .$transaction->order->transaction_number .' mendapat'.$hotpoint.' dari reward invite.');
        $this->assertTrue(true);

       // InviteReward::dispatch('INV/220427/HDID/0006');

       // check SUM admin fee from hitory transaction
       //$data =  (new InviteController())->users_reward('INV/220706/HDID/0004');
        //dd($data);
       
       
        // check count of users hild
    //    $user = User::where('id' , 9)->first();
    //    $this->actingAs($user)
    //    ->withSession(['foo' => 'bar']);

    //    $count_child=  (new InviteController())->count_of_child();
    //     //dd($count_child);

    //     // check reward mechanism 
    //     // rules 1-10 5% , 11-30 10% , > 30 20%

    //     $rules = (new InviteRules())->reward_mechanism($count_child , $data);

    //     dd($rules);
    } 

    
}
