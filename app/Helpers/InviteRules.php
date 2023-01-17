<?php
namespace App\Helpers;

use App\Hotpoint;
use App\InviteRules as AppInviteRules;
use App\OrderDetail;
use App\User;

class InviteRules {

    public static function generate_referal_code($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand(0, $charactersLength - 1)];

        }
        return $randomString;
    }

    public static function count_of_child($user_id){
        if(!$user_id){
            return 0;
        }
        $total_child = User::where('parent_id' , $user_id)->count();
        return $total_child;
    }

    public static function give_reward ($order_detail_id){
        if(!Hotpoint::where('order_detail_id' , $order_detail_id)->first()){
            return true;
        }
        return false;
    }

    public static function users_reward($invoice_number){
        $total_reward = 0;
        $transaction_by_invoice = OrderDetail::where('invoice_number', $invoice_number)->with('order_products')->first();

        if(!$transaction_by_invoice){
            return $total_reward;
        }
        foreach($transaction_by_invoice->order_products as $admin_fee){
            $total_reward += ($admin_fee->admin_fee - ($admin_fee->price - $admin_fee->fix_price));
        }
        return $total_reward;
    }

    public static function reward_mechanism($count_of_child, $total_admin_fee){

        $percentage = AppInviteRules::where('start_date' , '<=' , date('Y-m-d H:i:s'))
                      ->where('end_date' , '>=' , date('Y-m-d H:i:s'))
                      ->where('count_from' , '<=' , $count_of_child)
                      ->where('count_after' , '>=' , $count_of_child)
                      ->first();
                      
        if(!$percentage){
            return 0;
        }
        return (($percentage->percentage / 100) * $total_admin_fee);
    }

}