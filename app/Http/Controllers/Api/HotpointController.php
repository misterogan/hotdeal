<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Hotpoint as HelpersHotpoint;
use App\Helpers\OTP;
use App\Hotpoint;
use App\HotpointSetting;
use App\Http\Resources\StrengthsResource;
use App\Order;
use App\Strength;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class HotpointController extends Api
{
    public function use_hotpoints(Request $request) {
        $user = $this->user();

        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $validation = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        $hp_setting = HotpointSetting::where('user_id', $user->id)->first();

        if (Hash::check($request->password, $hp_setting->password)) {
            $query = Hotpoint::where('user_id', $user->id)->orderByDesc('updated_at')->first();
            $before = $query->after;

            $use_hotpoint = Hotpoint::create([
                'user_id' => $user->id,
                'type' => 'use',
                'value' => $request->value,
                'before' => $before,
                'after' => $request->value + $before,
                'code' => $request->code,
                'detail' => $request->detail,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($use_hotpoint->first()) {
                return $this->successResponse($use_hotpoint);
            } else {
                return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
            }
        } else {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_PASSWORD_NOT_MATCH_CODE_);
        }
    }

    public function check_password(Request $request) {
        $user = $this->user();

        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $validation = Validator::make($request->all(), [
            'pin' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }
        $response = HelpersHotpoint::validate_used_point($user , $request->pin);
        return $this->successResponse($response);
    }

    public function use_hotpoint_checkout(Request $request) {
        $user = $this->user();

        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $validation = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        $order = Order::where('order_id', $request->order_id)->where('user_id', $user->id)->first();
        $hotpoint = Hotpoint::where('user_id', $user->id)->orderByDesc('updated_at')->first();
        $payable = $order->total_payment - $order->total_discount;

        if ($payable >= $hotpoint->after) {
            $use_hotpoint = Hotpoint::create([
                'user_id' => $user->id,
                'type' => 'use',
                'value' => $hotpoint->after,
                'before' => $hotpoint->after,
                'after' => 0,
                'code' => $request->code,
                'detail' => $request->detail,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $use_hotpoint = Hotpoint::create([
                'user_id' => $user->id,
                'type' => 'use',
                'value' => $payable,
                'before' => $hotpoint->after,
                'after' => $hotpoint->after - $payable,
                'code' => $request->code,
                'detail' => $request->detail,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return $this->successResponse($use_hotpoint);
    }

    public function send_otp(Request $request) {
        $user = $this->user();

        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $otp = OTP::otp_by_phone_for_hotpoints($user->id, $user->email, $user->phone);

        if ($otp[0] == false) {
            return false;
        }

        return true;
    }

    public function check_otp(Request $request) {
        $user = $this->user();

        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $validation = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        $hp = HotpointSetting::where('user_id', $user->id)->first();

        if ($hp->otp != $request->otp) {
            return false;
        }

        $hp->otp = null;
        $hp->requested_at = null;
        $hp->expired_at = null;
        $hp->updated_at = Carbon::now();
        $hp->updated_by = 'system';

        if ($hp->save()) {
            return true;
        } else {
            return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
        }
    }

    public function set_password(Request $request) {
        $user = $this->user();
        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }
        $validation = Validator::make($request->all(), [
            'pin' => 'required',
            'confirm' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse('Semua field harus diisi', 201);
        }

        if($request->pin !== $request->confirm){
            return $this->errorResponse('Konfirmasi pin tidak sama',201);
        }
        $check_if_has_password = HotpointSetting::where('user_id' , $user->id)->first();
        // if($check_if_has_password){
        //     return $this->errorResponse('Kamu sudah pernah mengisi Pin hotpoint' , 300);
        // }
        if($check_if_has_password){
            HotpointSetting::where('user_id', $user->id)->update([
                'password' => Hash::make($request->confirm),
                'tries' => 0
            ]);
            return $this->successResponse([] , 'Berhasil membuat PIN' , 200);
        } else{
            $hp = HotpointSetting::create([
                'password' => Hash::make($request->confirm),
                'user_id' => $user->id,
                'updated_at' => Carbon::now(),
                'updated_by' => $user->id,
                'created_by' => $user->id,
            ]);
            if ($hp) {
                return $this->successResponse([] , 'Berhasil membuat PIN' , 200);
            }
        return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA , self::ERROR_WHEN_SAVE_DATA_CODE);
        }
    }

    public function update_password(Request $request) {
        $user = $this->user();
        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $validation = Validator::make($request->all(), [
            'lastpin' => 'required',
            'pin' => 'required',
            'confirm' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse('Semua field harus diisi',201);
        }

        if($request->pin !== $request->confirm){
            return $this->errorResponse('Konfirmasi pin tidak sama',201);
        }
        
        $get_password = HotpointSetting::where('user_id' , $user->id)->first();
        $check_if_password_match = $this->validate_password($request->lastpin , $get_password->password);

        if(!$check_if_password_match){
            return $this->errorResponse('Pin lama salah' , 300);
        }

        $hp = HotpointSetting::where('user_id' , $user->id)->update([
            'password' => Hash::make($request->confirm),
            'user_id' => $user->id,
            'updated_at' => Carbon::now(),
            'created_by' => $user->id,
        ]);

        if ($hp) {
            return $this->successResponse([] , 'Berhasil mengubah PIN' , 200);
        }

        return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA , self::ERROR_WHEN_SAVE_DATA_CODE);
    }

    public function validate_password($string1, $string2){
        if (Hash::check($string1, $string2)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_hotpoint(){
        $user = $this->user();
        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }

        $point = User::where('id', $user->id)->pluck('point')->first();
        return $this->successResponse($point);
    }
}
