<?php

namespace App\Http\Controllers\Admin;

use App\Coupon;
use App\CouponDetail;
use App\Http\Controllers\Controller;
use App\MasterPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    public function index(){
        return view('admin.coupon.index');
    }

    public function get_dt(Request $request){
        $coupon = Coupon::orderByDesc('created_at');
        return DataTables::of($coupon->get())->addIndexColumn()->make(true);
    }

    public function create_view(Request $request){
        $partners = MasterPartner::where('status','active')->get();
        return view('admin.coupon.create',compact('partners'));
    }

    public function edit_view(Request $request){
        $coupon = Coupon::where('id',$request->id)->first();
        $partners = MasterPartner::where('status','active')->get();
        return view('admin.coupon.edit',compact('partners','coupon'));
    }


    public function submit(Request $request){

        $user = Auth::user();
        $validation = Validator::make($request->all(), [
            'name'          => 'required',
            'start_date'    => 'required',
            'expired_date'  => 'required',
            'total_coupon'  => 'required',
            'partner'       => 'required',
            'serial_code'   => 'required',
            'length_code'   => 'required',
            'hotpoint_value'=> 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        DB::beginTransaction();
        try {

            $coupon = Coupon::create([
               'coupon_name'    =>$request->name,
               'start_date'     =>$request->start_date,
               'expired_date'   =>$request->expired_date,
               'total_coupon'   =>$request->total_coupon,
               'status'         =>$request->status,
               'partner_id'     =>$request->partner,
               'serial_code'    =>$request->serial_code,
               'length_code'    =>$request->length_code,
                'hotpoint'       => $request->hotpoint_value,
                'created_by'    => $user->name,
            ]);

            for ($i = 0; $i < $request->total_coupon; $i++) {
                $coupon_detail = CouponDetail::create([
                    'coupon_id'     =>$coupon->id,
                    'code'          =>$this->generate_code($request->serial_code,$request->length_code),
                    'isActive'      =>true,
                    'created_by'    => $user->name,
                ]);
            }

            DB::commit();
            return json_encode(['status'=> true, 'message'=> 'Success']);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function edit(Request $request){
        $user = Auth::user();

        DB::beginTransaction();
        try {

            $coupon = Coupon::where('status', 'active')->where('id', $request['coupon_id'])->first();
            $coupon->coupon_name = $request['name'];
            $coupon->hotpoint = $request['hotpoint_value'];
            $coupon->partner_id = $request['partner'];
            $coupon->serial_code = $request['serial_code'];
            $coupon->serial_code = $request['serial_code'];
            $coupon->total_coupon = $request['total_coupon'];
            $coupon->start_date = $request['start_date'];
            $coupon->expired_date = $request['expired_date'];
            $coupon->save();

            for ($i = 0; $i < $request->total_coupon; $i++) {
                $coupon_detail = CouponDetail::create([
                    'coupon_id'     =>$coupon->id,
                    'code'          =>$this->generate_code($request['serial_code'],$request['length_code']),
                    'isActive'      =>true,
                    'created_by'    => $user->name,
                ]);
            }

            DB::commit();
            return json_encode(['status'=> true, 'message'=> 'Success', 'id'=>$coupon->id]);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function generate_code($serial_code,$length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand(0, $charactersLength - 1)];

        }
        return $serial_code.$randomString;
    }

    public function get_detail_dt(Request $request){
        $coupon_detail = CouponDetail::where('coupon_id',$request->coupon_id)->where('isActive', true)->orderByDesc('created_at');
        return DataTables::of($coupon_detail->get())->addIndexColumn()->make(true);
    }

    public function disable_coupon_detail(Request $request){
        CouponDetail::where('id', $request->id_coupon_detail)->update([
            'isActive' => false,
        ]);
        return json_encode(['status'=> true, 'message'=> 'Berhasil inactive code']);
    }
}
