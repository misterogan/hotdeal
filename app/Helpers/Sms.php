<?php
namespace App\Helpers;

use App\Order;
use App\OrderDetail;
use App\OtpCode;
use Carbon\Carbon;
use http\Cookie;
use Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class Sms {
    static $SMS_URL = 'https://alpha.zenziva.net/apps/smsapi.php';
    static $USER_KEY = "dwzrko";
    static $PASS_KEY = "kotakasabla";

    public static function send($num, $msg) {
        $sms = [
            'nohp' => $num,
            'pesan' => $msg,
        ];
        $baseUrl = self::$SMS_URL;
        $config = [
            'userkey' => self::$USER_KEY,
            'passkey' => self::$PASS_KEY
        ];
        $params = array_merge($config, $sms);
        $uri = $baseUrl . '?' . http_build_query($params);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $uri);
        $result = curl_exec($curl);
        header('Content-type: application/xml');
        return true;
    }

    public static function otp($email,$num){
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $total_otp = OtpCode::whereDate('requested_at',$today)->where('email', $email)->count();
        if($total_otp > 3){
            return false;
        }else{
            $otp_code = self::otp_number();
            $otp = OtpCode::create([
                'email'=> $email,
                'phone_number'=> $num,
                'code'=> $otp_code,
                'requested_at'=>$now,
                'expired_at'=> Carbon::now()->addMinute(5),
                'created_at'=> $now,
            ]);
            if($otp){
                $msg = 'Gunakan kode Otp :'.$otp_code.' untuk masuk ke Hotdeal';
                //if(!self::send($num,$msg)) return false;
            }
        }
        return true;
    }

   public static function otp_number($n = 6) {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
        return $result;
    }
}
