<?php
namespace App\Helpers;

use App\MasterStatusOrder;
use App\OrderDetail;
use App\OrderDetailLog;
use App\OtpCode;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Emails {

    public static $PASSWORD = 'password';
    public static $OTP = 'otp';
    public static $verify_email_otp = 'verify_email_register';
    public static $OTP_FORGET = 'otp_forget';
    public static $VENDOR_PAYMENT = 'vendor_payment';
    public static $SEND_HOTPOINT = 'send_hotpoint';

    public static function send_email($to_name = null, $to_email, $subject, $content, $template = null) {
        switch ($template) {
            case 'password':
                $view = 'emails.password';
                break;
            case 'otp':
                $view = 'emails.otp';
                break;
            case 'otp_forget':
                $view = 'emails.otp_forget';
                break;
            case 'vendor_payment':
                $view = 'emails.vendor_order_payment';
                break;
            case 'send_hotpoint':
                $view = 'emails.send_hotpoint';
                break;
            case 'verify_email_register':
                $view = 'emails.verify_email_otp';
                break;
            default:
                $view = 'emails.mail';
        }

        $data = ['name' => $to_name, 'content' => $content];

        try {
            Mail::send($view, $data, function ($message) use ($to_name, $to_email, $subject) {
                $message->to($to_email, $to_name)->subject($subject);
                $message->from('noreply@hotdeal.id', 'Hotdeal Automated Emails');
            });
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public static function save_email_log($data){
//        EmailLog::create($data);
    }

    public static function otp($email,$num) {
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $total_otp = OtpCode::whereDate('requested_at',$today)->count();
        if($total_otp > 3){
            return false;
        }else{
            $otp_code = self::otp_number();
            $otp = OtpCode::create([
                'email'=> $email,
                'phone_number'=> $num,
                'code'=> $otp_code,
                'requested_at'=>$now,
                'expired_at'=> Carbon::now()->addMinutes(5),
                'created_at'=> $now,
            ]);
            if($otp) {
                self::send_email($email, $email, 'Hotdeal: OTP Code', $otp_code, self::$OTP);
            }
        }
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
