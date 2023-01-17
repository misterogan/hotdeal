<?php

namespace App\Http\Controllers\Admin;

use App\Hotpoint;
use App\Http\Controllers\Controller;
use App\Order;
use App\RejekiNomplokCoupon;
use App\User;
use App\UserActivity;
use App\UserSearchLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request){
        return view('admin.user.index');
    }

    public function edit_view(Request $request) {
        $user_id = $request->id;
//        $user_id = 15;
        $user = User::where('id', $user_id)->with('province')->with('user_addresses')->first();
        $orders = Order::where('user_id',$user_id)->orderBy('created_at','DESC')->get();
        $hotpoints = Hotpoint::where('user_id',$user_id)->orderBy('created_at','DESC')->get();
        $rejeki_nomplok = RejekiNomplokCoupon::select('rejeki_nomplok_weeks.week','rejeki_nomplok_coupons.product_id','rejeki_nomplok_coupons.coupon_number','rejeki_nomplok_coupons.status','rejeki_nomplok_coupons.created_at','products.name')
                            ->leftjoin('rejeki_nomplok_weeks','rejeki_nomplok_coupons.rejeki_nomplok_week_id','=', 'rejeki_nomplok_weeks.id')
                            ->leftjoin('products','rejeki_nomplok_coupons.product_id','=', 'products.id')
                            ->where('rejeki_nomplok_coupons.user_id',$user_id)->orderBy('created_at','desc')->get();
        $user_activity = UserActivity::where('user_id', $user_id)->orderByDesc('id')->get();
        return view('admin.user.edit', compact('user_id', 'user','orders','hotpoints','rejeki_nomplok', 'user_activity'));
    }

    public function user_dt() {
        $users = User::where('is_vendor' , false)->with('user_addresses')->orderByDesc('id');

        return DataTables::of($users->get())->addIndexColumn()->make(true);
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $admin = Auth::user();
        $user = User::where('id', $request->user_id)->first();

        $user->status = $request->status;
        $user->updated_by = $admin->name;
        $user->updated_at = Carbon::now();

        if ($user->save()) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> 'Failed']);
        }
    }

    public function searches_index(){
        return view('admin.user.searches');
    }

    public function searches_dt(){
        $searches = UserSearchLog::select('user_id', 'keyword', 'created_at')
                ->with('user')
                ->where('keyword', '!=', '')
                ->orderByDesc('created_at');

        return DataTables::of($searches->get())->addIndexColumn()->make(true);
    }
}
