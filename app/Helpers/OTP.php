<?php
namespace App\Helpers;

use App\HotpointSetting;
use App\MasterStatusOrder;
use App\OrderDetail;
use App\OrderDetailLog;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Order;
use App\OtpCode;
use App\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class OTP {

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
            $check_user_phone = User::where('phone',$num)->where('is_phone_verified',false)->first();
            if($check_user_phone){
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
                    if(!self::send($num,$msg)) return false;
                }
            }else{
                return [false , 'Nomor telepon telah terverifikasi sebelumnya'];
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


    public static function otp_by_phone($email , $phone){
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $total_otp = OtpCode::whereDate('requested_at',$today)->where('email', $email)->count();
        if($total_otp > 3){
            return [false , 'OTP sudah dikirim lebih dari 3 kali, Coba lagi besok'];
        }else{
            $otp_code = self::otp_number();
            $otp = OtpCode::create([
                'phone_number'=> $phone,
                'email' => $email,
                'code'=> $otp_code,
                'requested_at'=>$now,
                'expired_at'=> Carbon::now()->addMinute(5),
                'created_at'=> $now,
            ]);
            if($otp){
                $msg = 'Gunakan kode Otp :'.$otp_code.' untuk masuk ke Hotdeal';
                if(!self::send($phone,$msg))  return [false , 'Gagal mengirim OTP'];
            }
        }
        return [true,''];
    }

    public static function otp_by_phone_for_hotpoints($user_id, $email , $phone){
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $total_otp = OtpCode::whereDate('requested_at',$today)->where('email', $email)->count();
        if($total_otp > 3){
            return [false , 'OTP sudah dikirim lebih dari 3 kali, Coba lagi besok'];
        }else{
            $otp_code = self::otp_number();
            $otp = OtpCode::create([
                'phone_number'=> $phone,
                'email' => $email,
                'code'=> $otp_code,
                'requested_at'=>$now,
                'expired_at'=> Carbon::now()->addMinutes(5),
                'created_at'=> $now,
            ]);
            $hp_setting = HotpointSetting::where('user_id', $user_id)->update([
                'otp' => $otp_code,
                'requested_at' => $now,
                'expired_at' => Carbon::now()->addMinutes(5),
                'updated_at' => Carbon::now(),
                'updated_by' => 'system',
            ]);
            if($otp){
                $msg = 'Gunakan kode Otp :'.$otp_code.' untuk reset password Hotpoint';
                if(!self::send($phone,$msg))  return [false , 'Gagal mengirim OTP'];
            }
        }
        return [true,''];
    }

    public static function otp_by_email($email){
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');

        $total_otp = OtpCode::whereDate('requested_at',$today)->where('email', $email)->count();
        if($total_otp > 3){
            return [false , 'OTP sudah dikirim lebih dari 3 kali, Coba lagi besok'];
        }else{
            try {
                DB::beginTransaction();

                $otp_code = self::otp_number();
                $otp = OtpCode::create([
                    'email'=> $email,
                    'code'=> $otp_code,
                    'requested_at'=>$now,
                    'expired_at'=> Carbon::now()->addMinutes(5),
                    'created_at'=> $now,
                ]);
                if($otp){
                    $user = User::where('email', $email)->first();
                    $send_otp = Emails::send_email($user->name, $user->email, 'Verification OTP', $otp_code, Emails::$OTP);
                    if(!$send_otp){
                        return [false , 'Gagal mengirim OTP'];
                    }
                }
                DB::commit();
            } catch (Throwable $e) {    
                DB::rollBack();
                return [false , $e->getMessage()];
            }
        }
        return [true,''];
    }

    public static function verify_otp_by_email($email){
        $now = date('Y-m-d H:i:s');
        try {
            DB::beginTransaction();
            $otp_code = self::otp_number();
            $otp = OtpCode::create([
                'email'=> $email,
                'code'=> $otp_code,
                'requested_at'=>$now,
                'expired_at'=> Carbon::now()->addMinutes(5),
                'created_at'=> $now,
            ]);
            if($otp){
                $send_otp = Emails::send_email('', $email, 'Verifikasi Email', $otp_code, Emails::$verify_email_otp);
                if(!$send_otp){
                   return false;
                }
            }
            DB::commit();
        } catch (Throwable $e) {  
            return [false , $e->getMessage()];  
            DB::rollBack();
            return false;
        }
        return true;
    }

}
