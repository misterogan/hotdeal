<?php
namespace App\Helpers;

class TransactionEmailTemplate {
    public static $title_confirmation = 'PLEASE COMPLETE YOUR PAYMENT PROCESS';
    //public static $body_header_order_confirmation = 'Terima kasih telah memilih Hotdeal Indonesia sebagai surga belanja terfavorit kamu. Silahkan selesaikan proses pembayaran kamu paling lambat {expired} agar pesanan kamu bisa segera kami proses.';
    public static $body_header_order_confirmation = 'Terima kasih telah memilih Hotdeal Indonesia sebagai surga belanja terfavorit kamu. Silahkan selesaikan proses pembayaran kamu agar pesanan kamu bisa segera kami proses.';
    public static $title_paid = 'HOTDEAL - PAYMENT ACCEPTED';
    public static $body_header_order_paid = 'Wah, kabar baik buat kamu! Pembayaranmu telah kami terima dan konfirmasi. Kami akan mengirimkan notifikasi saat pesananmu sudah siap dikirim menuju tempatmu!.';
    
    public static $title_shipped = 'YOUR ORDER IS ON ITS WAY!';
    public static $body_header_order_shipped = 'Pesanan sedang dalam proses pengiriman ke tempat tujuanmu. Sebentar lagi kamu bisa mendapatkan produk eksklusif terbaik dari Hotdeal Indonesia.';
    
    public static $title_delivered = 'YOUR ORDER HAS ARRIVED';
    public static $body_header_order_delivered = 'Yeayy, pesananmu sudah sampai nih!';


    public static $body_header_order_confirmation_vendor = 'Pembayaran pesanan {nomor_invoice} sudah terkonfirmasi. Mohon segera kirimkan pesanan ke {user_name}. Silahkan login ke akun Hotdeal Seller Anda untuk menyelesaikan pesanan produk berikut.';
    public static $body_header_order_delivery = 'Pesanan {nomor_invoice} sedang dalam proses pengiriman oleh jasa ekspedisi. ';
    public static $body_header_order_delivered_vendor = 'Pesanan {nomor_invoice} sudah sampai!';

    public static function check_body($type , $expired){
        if($type == 'confirmation'){
            return str_replace('{expired}' , $expired , self::$body_header_order_confirmation);
        }else if($type == 'paid'){
            return self::$body_header_order_paid;
        }else if($type == 'accept'){
            return self::$body_header_order_shipped;
        }else if($type == 'delivered'){
            return self::$body_header_order_delivered;
        }       
    }

    public static function vendor_body_email($type , $expired){
        if($type == 'delivery'){
            return self::$body_header_order_delivery;
        }else if($type == 'accept'){
            return self::$body_header_order_paid;
        }else if($type == 'paid'){
            return self::$body_header_order_confirmation_vendor;
        }else if($type == 'delivered'){
            return self::$body_header_order_delivered_vendor;
        }     
    }
}
