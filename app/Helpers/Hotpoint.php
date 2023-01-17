<?php
namespace App\Helpers;

use App\HotpointSetting;
use App\RefundLogs;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class Hotpoint {

     const CODE = [
         'EFI001' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Invite" , 'description' => 'Earning From Invite / reward transaksi dari invite member'],
         'EFM001' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Membership" , 'description' => 'Earning From Member / untuk member cashtree'],
         'EFC001' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Transaksi Cancel" , 'description' => 'Earning From Cancel Order /point dari transaksi gagal/tidak diproses'],
         'EFE001' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Event" , 'description' => 'Earning From Event / Survei'],
         'EFE002' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Event" , 'description' => 'Earning From Event / Ide produk'],
         'EFREFUND' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Transaksi Refund" , 'description' => 'Earning From Refund traksaction / transaction'],
         'refund' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Transaksi Refund" , 'description' => 'Earning From Refund traksaction / transaction'],
         'EFVOUCHER' => ['image' => '/img/assets_transaction_hotpoint.svg' , 'type' => 'earn' , 'title' => "Voucher" , 'description' => 'Earning From voucher / voucher client'],
         'UTR001' => ['image' => '/img/assets_using_hotpoint.svg' , 'type' => 'use' ,  'title' => "Pembayaran" , 'description' => 'Using Transacton /Penggunaan Point dalam transaksi'],
         'HOTE001' => ['image' => '/images/badge_rejeki_nomplok.svg' , 'type' => 'earn' ,  'title' => "Rejeki Nomplok" , 'description' => 'Earning Point From Rejeki Nomplok / Mendapat point dari rejeki nomplok'],
     ];
      
    public static function send($user_id, $amount, $code, $detail , $order_detail_id = null) {
        $prev_hotpoints = \App\Hotpoint::where('user_id', $user_id)->orderByDesc('created_at')->first();
        if ($prev_hotpoints) {
            $before = $prev_hotpoints->after;
        } else {
            $before = 0;
        }
        $after = $before + $amount;

        $hotpoints = \App\Hotpoint::create([
            'user_id' => $user_id,
            'type' => 'earn',
            'value' => $amount,
            'before' => $before,
            'after' => $after,
            'code' => $code,
            'detail' => $detail,
            'order_detail_id' => $order_detail_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $update_users = User::where('id' , $user_id)->update(['point' => $after]);
        if(!$update_users){
            return false;
        }
        if ($hotpoints->first()) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function validate_used_point($user , $pin){
        $point = HotpointSetting::where('user_id' , $user->id)->first();
        
        if(!$point){
            return ['status' => false , 'message' => 'Pin belum disetting'];
        }
        if($point->try_again_in > Carbon::now()){
            $diff = Carbon::now()->diffInMinutes($point->try_again_in);
            return ['status' => false , 'message' => 'Kamu sudah melebih batas percobaan , Tunggu '.$diff.' menit untuk mencoba lagi.'];
        }

        if($point->tries > 4){
            HotpointSetting::where('user_id' , $user->id)->update([
                'try_again_in' => Carbon::now()->addMinutes(30),
                'expired_at' =>  Carbon::now()->addMinutes(5),
                'requested_at' =>  Carbon::now(),
                'tries' => 0
            ]);
            return ['status' => false , 'message' => 'Kamu sudah melebih batas percobaan , Tunggu 30 menit untuk mencoba lagi.'];
        }
        if (Hash::check($pin, $point->password)) {
            HotpointSetting::where('user_id' , $user->id)->update([
                'tries' => 0,
                'expired_at' =>  Carbon::now()->addMinutes(5),
                'requested_at' =>  Carbon::now(),
            ]);
            return ['status' => true , 'message' => 'success'];

        } else {
            HotpointSetting::where('user_id' , $user->id)->update([
                'tries' => $point->tries + 1,
                'requested_at' =>  Carbon::now(),
                'updated_at' =>  Carbon::now(),
            ]);
            return ['status' => false , 'message' => 'Pin salah'];
        }

        return ['status' => false , 'message' => 'Pin salah'];

    }
    public static function check_used_point($user , $used){
        
        $validate = HotpointSetting::where('user_id' , $user->id)->first();
        if(strtotime($validate->expired_at) < strtotime(Carbon::now())){
            return ['status' => false , 'message' => 'Pin expired'];
        }
        $point = User::select('point')->where('id' , $user->id)->where('status' , 'active')->first();
        
        if($used <= $point->point){
            return ['status' => true , 'message' => 'Success'];
        }else{   
            return ['status' => false , 'message' => 'Penggunaan melebihi poin'];
        }
        return ['status' => false , 'message' => 'Poin tidak dapat digunakan'];
    }

    public static function using_point($user_id , $amount , $code , $detail){
        $prev_hotpoints = \App\Hotpoint::where('user_id', $user_id)->orderByDesc('id')->first();
        if ($prev_hotpoints) {
            $before = $prev_hotpoints->after;
        } else {
            $before = 0;
        }
        $after = $before - $amount;
        $hotpoints = \App\Hotpoint::create([
            'user_id' => $user_id,
            'type' => 'use',
            'value' => $amount,
            'before' => $before,
            'after' => $after,
            'code' => $code,
            'detail' => $detail,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        $update_point = User::select('point')->where('id' , $user_id)->first();
        
        if($update_point->point != $prev_hotpoints->after){
            return false;
        }
        if(!User::where('id' , $user_id)->update(['point' => $after])){
            return false;
        }
        if ($hotpoints->first()) {
            return true;
        } else {
            return false;
        }
    }
    
}
