<?php

namespace App\Http\Controllers\Api\Coupon;

use App\CouponDetail;
use App\Helpers\Hotpoint;
use App\Http\Controllers\Api\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponPartnerResource;
use App\MasterPartner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponController extends Api
{
    public function set_email(Request $request){

        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse('Data kurang lengkap','201');
        }
        $now = Carbon::now();
        $token = $request->token;
        $api_key_validation = MasterPartner::where('status', 'active')->where('token', $token)->first();
        if(!$api_key_validation){
            return $this->errorResponse(self::ERROR_COUPON_AUTH_VALIDATION, self::ERROR_COUPON_CODE);
        } else{
            $coupon = CouponDetail::where('isActive', true)
                    ->where('status', 'available')
                    ->where('code', $request->code)
                    ->first();
            if($coupon){
                $coupon->email = $request->email;
                $coupon->buy_date = $now;
                $coupon->status = 'buy';
                $coupon->save();
                $response = [
                    'headers' => [
                        'Authorization' => 'Bearer '.$token,
                        'Accept' => 'application/json',
                    ],
                    'coupons' => $coupon
                ];
                return $this->successResponse($response);
            } else{
                return $this->errorResponse('Kupon Tidak Ditemukan', self::ERROR_COUPON_CODE);
            }
        }

    }

    public function claim_coupon(Request $request){
        $user = $this->user();
        if(!$user){
            return $this->errorResponse('Silahkan login dahulu sebelum melakukan redeem.',self::ERROR_COUPON_CODE);
        }
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse('Silahkan isi kode/email saat pembelian.',self::ERROR_COUPON_CODE);
        }
        $now = Carbon::now();
        $email = $request->email;

        $coupon = CouponDetail::where('isActive', true)
                    ->with('value_hotpoint')
                    //->where('status', 'buy')
                    ->where('code', $request->code)
                    ->first();

        if(!$coupon){
            return $this->errorResponse('Kode voucher dengan email tidak ditemukan.', self::ERROR_COUPON_CODE);
        }else{
            if($coupon->status == 'redeem'){
                return $this->errorResponse('Voucher sudah di redeem.', self::ERROR_COUPON_CODE);
            }
            if($coupon->status != 'buy'){
                return $this->errorResponse('Kode voucher dengan email tidak ditemukan.', self::ERROR_COUPON_CODE);
            }
            if($coupon->email == $email){
                try {
                    DB::beginTransaction();
                    $coupon->claim_date = $now;
                    $coupon->email = $request->email;
                    $coupon->status = 'redeem';
                    $coupon->save();
                    Hotpoint::send( $user->id , $coupon->value_hotpoint->hotpoint , 'EFVOUCHER' , 'Mendapatkan point dari voucher '.$request->code);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return $this->errorResponse('Terjadi kesalahan saat redeem voucher ' , 500);
                }
            }else{
                return $this->errorResponse('Kupon Tidak Sesuai', self::ERROR_COUPON_CODE);
            }

        }
        return $this->successResponse([] , 'Selamat , Hotpoint kamu sudah ditambahkan.');
    }

    public function check_coupon(Request $request){  
        $user = $this->user();
        if(!$user){
            return $this->errorResponse('Silahkan login dahulu sebelum melakukan redeem.',self::ERROR_COUPON_CODE);
        }
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse('Silahkan isi Kode/Email saat pembelian',self::ERROR_COUPON_CODE);
        }
        $email = $request->email;
        $coupon = CouponDetail::where('isActive', true)
                    //->where('status', 'buy')
                    ->where('code', $request->code)
                    ->first();
        if(!$coupon){
            return $this->errorResponse('Kode voucher dengan email '.$email.' tidak ditemukan.', self::ERROR_COUPON_CODE);
        }else{
            if($coupon->status == 'redeem'){
                return $this->errorResponse('Voucher sudah di redeem.', self::ERROR_COUPON_CODE);
            }
            if($coupon->status == 'buy' && $coupon->email != $email){
                return $this->errorResponse('Voucher tidak tersedia.', self::ERROR_COUPON_CODE);
            }
            return $this->successResponse( new CouponPartnerResource($coupon));
        }
    }
}


