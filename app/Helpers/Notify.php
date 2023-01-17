<?php
namespace App\Helpers;

use App\MediaLog;
use App\Notification;
use App\NotificationDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Throwable;

class Notify {

    static $PAYMENT_TITLE = 'Pembayaran';
    static $PAYMENT_BODY = 'Hi {name}, Silahkan transfer {nominal} ke {bank} {account} sebelum {expired}.';
    static $PAYMENT_SUCCESS_TITLE = 'Pembayaran Berhasil';
    static $PAYMENT_SUCCESS_BODY = 'Hi {name}, Terimakasih telah melakukan pembayaran untuk transaksi {transaction_number}. Transaksi kamu akan segera kami proses.';
    static $ADMIN_PAYMENT_SUCCESS_BODY = 'Hi {name}, Transaksi dengan nomor pesanan {transaction_number} telah dibayar. Segera proses transaksi.';
    static $VENDOR_PAYMENT_SUCCESS_BODY = 'Hi {name}, Pembayaran untuk nomor transaksi {transaction_number} telah dilakukan. Cek transaksi.';

    
    static $PAYMENT_SUCCESS_BODY_ADMIN = 'Hi {name}, Pembayaran untuk nomor transaksi {transaction_number} telah dilakukan. Cek transaksi.';
    static $ORDER_PROCESSED_TITLE = 'Pesanan Siap Dikirim';
    static $ORDER_PROCESSED_BODY = 'Hi {name}, Pesanan untuk nomor invoice {invoice_number} siap dikirim.';
    static $ORDER_COMPLETED_TITLE = 'Pesanan Selesai';
    static $ORDER_COMPLETED_BODY = 'Hi {name}, Pesanan untuk nomor invoice {invoice_number} telah selesai.';

    
    static $REFUND_TITLE = 'Menunggu Respons';
    static $REFUND_URL = 'transactions/list-transaction';
    static $REFUND_TOPIC = 'REFUND';
    static $REFUND_CONFIRM_TITLE = 'Pengembalian Diajukan';
    static $REFUND_PROCESS_TITLE = 'Pengembalian Barang Diproses';
    static $REFUND_APPROVE_TITLE = 'Pengembalian Barang Diterima';


    static $PAYMENT_URL = '/transactions/pending-transaction';
    static $PAYMENT_SUCCESS_URL = '/transactions/list-transaction';
    static $PAYMENT_TOPIC = 'ORDER';
    static $PAYMENT_SEND_BY = 'hotdeal';
    static $ADMIN_SEND_TO = 'admin';

    static $order_cancel_title = "Pesanan Dibatalkan";
    static $order_cancel_title_auto = "Pesanan Sudah Tidak Berlaku";
    static $order_cancel_body_customer= "Pesanan {invoice_number} telah dibatalkan karena permintaan customer atau terjadi kendala pada pesanan yang telah dilakukan.";
    static $order_cancel_body_vendor= "Pesanan {invoice_number} telah dibatalkan oleh penjual.";
    static $order_cancel_body_auto= "Pesanan {invoice_number} dibatalkan dari sistem Hotdeal karena melewati batas pembayaran yang telah ditentukan. Mohon maaf atas ketidaknyamannya. Silahkan membuat pesanan kembali.";
    static $order_cancel_body_general= "Pesanan {invoice_number} dibatalkan dari sistem Hotdeal";

    static $rejeki_nomplok_winner_title = 'Pemenang Rejeki Nomplok';
    
    public static function send(
            $title,
            $body,
            $url,
            $image,
            $send_to,
            $topic,
            $user_id,
            $send_by="hotdeal"

    ){
        $now = date('Y-m-d H:i:s');
        // $notification = Notification::create([
        //     'title'=>$title,
        //     'body'=>'test',
        //     'url'=>$url,
        //     'image'=>$image,
        //     'send_to'=>$send_to,
        //     'topic'=>$topic,
        //     'status'=>'active',
        //     'created_at'=>$now,
        //     'send_by'=>$send_by,
        //     'browser'=>'website'
        // ]);
        // dd($notification);
        DB::beginTransaction();
        try{
            $notification = Notification::create([
                'title'=>$title,
                'body'=>$body,
                'url'=>$url,
                'image'=>$image,
                'send_to'=>$send_to,
                'topic'=>$topic,
                'status'=>'active',
                'created_at'=>$now,
                'send_by'=>$send_by,
                'browser'=>'website'
            ]);

            if($send_to == 'uid'){
                NotificationDetail::create([
                    'notification_id'=>$notification->id,
                    'user_id'=>$user_id,
                    'is_read'=>false
                ]);
            } 
            if ($send_to == 'admin') {
                NotificationDetail::create([
                    'notification_id'=>$notification->id,
                    'user_id'=> '0',
                    'is_read'=>false
                ]);
            }
            DB::commit();
        }
        catch (Throwable $e) {
            print_r($e->getMessage());
            DB::rollback();
        }
        return true;
    }

    public static function process($invoice_number){
        $title = '';
    }

}
