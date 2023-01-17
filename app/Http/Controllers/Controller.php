<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//    MESSAGE
    protected const TRANSACTION_ERROR_VALIDATION = "Validation Error";
    protected const ERROR_NOT_LOGIN = "Unauthenticated";
    protected const ERROR_NOT_AUTHORIZED = "Unauthorized";
    protected const ERROR_PASSWORD_NOT_MATCH = "Password tidak sama";
    protected const SUCCESS_CHANGE_PASSWORD = "Password berhasil diganti";
    protected const PRODUCT_NOT_FOUND = "Produk tidak ditemukan";
    protected const ERROR_WHEN_SAVE_DATA = "Gagal menyimpan data";
    protected const ERROR_WHEN_DATA_NOT_FOUND = "Data tidak ditemukan";
    protected const COUPON_NOT_FOUND = "Voucher tidak ditemukan";
    protected const COUPON_NOT_STARTED = "Periode Voucher belum dimulai";
    protected const COUPON_EXPIRED = "Periode Voucher telah kedaluwarsa";
    protected const COUPON_NOT_AVAILABLE = "Voucher tidak tersedia";
    protected const COUPON_NOT_MATCH = "Belum sesuai dengan persyaratan yang berlaku";
    protected const COUPON_IS_EMPTY = "Stok voucher sudah habis"; 
    protected const COUPON_HAS_USED = "Tidak bisa menggunakan voucher, karena telah terpakai";
    protected const ORDER_ERROR_SAVE_DATA = "Transaksi tidak dapat diproses";
    protected const ORDER_VOUCHER_ERROR_SAVE_DATA = "Voucher tidak dapat diproses";
    protected const ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH = "Transaksi tidak dapat diproses , ada perubahan harga";
    protected const PAYMENT_SUCCESS = "Pembayaran telah berhasil";
    protected const PAYMENT_FAILED = "Pembayaran dikonfirmasi gagal";
    protected const LOGIN_SOCIAL_FAILED = "login gagal";
    protected const INVALID_SOCIAL_PROVIDER = "Invalid credentials provided";
    protected const OTP_PROVIDER = "OTP sudah dikirim lebih dari 3 kali, Coba lagi besok";
    protected const OTP_WRONG = "Kode OTP Salah, Silahkan memasukkan kode yang benar";
    protected const OTP_FAILED_SEND_EMAIL = "Gagal mengirim OTP, Pastikan email anda aktif atau hubungi admin hotdeal";
    protected const OTP_FAILED_SEND_PHONE = "Gagal mengirim OTP, Pastikan nomor handphone kamu aktif atau hubungi admin hotdeal";
    protected const OTP_EXPIRED = "Kode OTP telah expired.";
    protected const CALLBACK_WRONG = "Invalid parameter!";
    protected const OUT_OF_STOCK = "Persediaan Habis";
    protected const ERROR_REQUIRED_FIELD = "silahkan isi semua field";
    protected const ERROR_MIN_PASSWORD = "Password minimal 6 huruf!";
    protected const ERROR_PASSWORD_NOT_MACTH = "Konfirmasi password tidak sama";
    protected const PHONE_MUST_VERIFY_ERROR = "Nomor telepon harus diverifikasi";
    protected const PHONE_HAS_VERIFY = "Nomor telepon telah terverifikasi sebelumnya";
    protected const UNIQUE_PHONE_NUMBER = "Nomor telepon sudah digunakan untuk akun lain";
    protected const TRANSACTION_NOT_FOUND = "Transaksi tidak ditemukan";
    protected const TRANSACTION_REDIRECT = "Gagal melakukan proses pembayaran";

    protected const PIN_WRONG = "Pin yang kamu masukkan salah";
    protected const HOTPOINT_CANT_USE = "Hotpoint tidak dapat digunakan";

    protected const CANT_GENERATE_REFERAL_INVITE_FRIEND = "Gagal membuat referal code!";


//    CODE
    protected const ERROR_VALIDATION_CODE_ = '201';
    protected const ERROR_MIN_PASSWORD_CODE = '208';
    protected const ERROR_PASSWORD_NOT_MATCH_CODE_ = '202';
    protected const SUCCESS_CHANGE_PASSWORD_CODE = '203';
    protected const ORDER_ERROR_SAVE_DATA_CODE = 204;
    protected const ORDER_VOUCHER_ERROR_SAVE_DATA_CODE = 205;
    protected const ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH_CODE = 206;
    protected const PHONE_MUST_VERIFY_ERROR_CODE = 207;
    protected const ERROR_NOT_LOGIN_CODE= '401';
    protected const ERROR_WHEN_DATA_NOT_FOUND_CODE= '402';
    protected const PRODUCT_NOT_FOUND_CODE= 405;
    protected const COUPON_NOT_FOUND_CODE= 406;
    protected const COUPON_EXPIRED_CODE= 408;
    protected const COUPON_NOT_STARTED_CODE= 407;
    protected const COUPON_NOT_AVAILABLE_CODE= 408;
    protected const COUPON_NOT_MATCH_CODE= 409;
    protected const PAYMENT_SUCCESS_CODE= 410;
    protected const PAYMENT_FAILED_CODE= 411;
    protected const LOGIN_SOCIAL_CODE = 412;
    protected const INVALID_SOCIAL_PROVIDER_CODE = 413;
    protected const PIN_WRONG_CODE = 414;
    protected const HOTPOINT_CANT_USE_CODE = 415;
    protected const ERROR_WHEN_SAVE_DATA_CODE = 500;
    protected const ERROR_OTP_PROVIDER_CODE = 501;
    protected const ERROR_OTP_WRONG_CODE = 501;
    protected const STATUS_CALLBACK_CANCELED = 502;
    protected const OUT_OF_STOCK_CODE = 503;
    protected const PASSWORD_NOT_MACTH_CODE_ = 504;
    protected const PHONE_HAS_VERIFY_CODE = 505;
    protected const ERROR_GENERATE_REFERAL_CODE = 506;
    


    //ORDER STATUS

    protected const STATUS_AWAITING_PAYMENT = 1;
    protected const STATUS_PENDING = 2;
    protected const STATUS_PROCESSED = 3;
    protected const STATUS_DELIVERY = 4;
    protected const STATUS_COMPLETED = 5;
    protected const STATUS_REFUND_REQUESTED = 6;
    protected const STATUS_REFUNDED = 7;
    protected const STATUS_USER_CANCELED = 8;
    protected const STATUS_VENDOR_CANCELED = 9;
    protected const STATUS_HOTDEAL_CANCELED = 10;


//    protected const STATUS_AWAITING_PAYMENT = "awaiting_payment";
//    protected const STATUS_PENDING = "pending";
//    protected const STATUS_PROCESSED = "processed";
//    protected const STATUS_DELIVERY = "delivery";
//    protected const STATUS_COMPLETED = "completed";
//    protected const STATUS_REFUND_REQUESTED = "refund_requested";
//    protected const STATUS_REFUNDED = "refunded";
//    protected const STATUS_USER_CANCELED = "user_canceled";
//    protected const STATUS_VENDOR_CANCELED = "vendor_canceled";
//    protected const STATUS_HOTDEAL_CANCELED = "hotdeal_canceled";
}
