<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\FAQ;
use App\FlashSale;
use App\FlashSaleDetail;
use App\Helpers\CouponsRejekiNomplok;
use App\Helpers\Hotpoint;
use App\Helpers\Notify;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\RejekiNomplokCouponsResources;
use App\OrderDetailProduct;
use App\Product;
use App\RejekiNomplokBanner;
use App\RejekiNomplokCoupon;
use App\RejekiNomplokProduct;
use App\RejekiNomplokWeek;
use App\RejekiNomplokWinners;
use App\RunningText;
use App\Strength;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Throwable;

class RejekiNomplokController extends Controller
{
    public function master_view(Request $request) {
        $banner = RejekiNomplokBanner::where('id', 1)->first();

        return view('admin.events.rejeki-nomplok.master.index', compact('banner'));
    }

    public function banner_view(Request $request) {
        $banner = RejekiNomplokBanner::where('id', 1)->first();

        if (empty($banner)){
            $banner = RejekiNomplokBanner::create([
                'id' => 1,
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'banner' => null,
                'banner_mobile' => null
            ]);
            return view('admin.events.rejeki-nomplok.master.banner', compact('banner'));
        } else {
            return view('admin.events.rejeki-nomplok.master.banner', compact('banner'));
        }

    }

    public function products_view(Request $request) {
        return view('admin.events.rejeki-nomplok.products.index');
    }

    public function winners_view(Request $request) {
        return view('admin.events.rejeki-nomplok.winners.index');
    }

    public function coupons_view(Request $request) {
        return view('admin.events.rejeki-nomplok.coupons.index');
    }

    public function coupons_detail_view(Request $request) {
        $week_id = $request->id;

        return view('admin.events.rejeki-nomplok.coupons.detail', compact('week_id'));
    }

    public function master_dt() {
        $master = RejekiNomplokWeek::orderBy('id' ,'ASC');

        return DataTables::of($master->get())->addIndexColumn()->make(true);
    }  

    public function delete_image(Request $request){
        RejekiNomplokBanner::where('id', 1)->update([
            'banner' => '',
            'banner_mobile' => ''
        ]);

    }

    public function products_dt() {
        $products = RejekiNomplokProduct::with('product')->where('status', 'active')->orderByDesc('created_at');
 
        return DataTables::of($products->get())->addIndexColumn()->make(true);
    }

    public function winners_dt() {
        $winners = RejekiNomplokWinners::with(['week', 'user', 'coupon', 'product'])->orderByDesc('created_at');
        // dd($winners);
        return DataTables::of($winners->get())->addIndexColumn()->make(true);
    }

    public function coupons_dt(Request $request) {

        if($request->week != null){
            $week = RejekiNomplokWeek::where('week' , $request->week)->where('status','active')->first();
            $coupons = RejekiNomplokCoupon::with(['user', 'week', 'order.product', 'product']);
            if($request->ihsg){
                $last_number = CouponsRejekiNomplok::getLastNumberIhsg($request->ihsg);
                $coupons->where('coupon_number' , $last_number);
            }
            $coupons->where('rejeki_nomplok_week_id' , $week->id)->orderByDesc('created_at');
            $data = RejekiNomplokCouponsResources::collection($coupons->get());
            return DataTables::of($data)->addIndexColumn()->make(true);
        }else{
            $coupons = RejekiNomplokCoupon::with(['user', 'week', 'order', 'order.product', 'product']);
            $data = RejekiNomplokCouponsResources::collection($coupons->orderBy('rejeki_nomplok_coupons.id' , 'DESC')->get());
            return DataTables::of($data)->addIndexColumn()->make(true);
        }
    
    }

    public function coupons_dt_winner(Request $request) {
        if($request->week != null){
            $week = RejekiNomplokWeek::where('week' , $request->week)->where('status','active')->first();
            if($request->ihsg){
                $coupons = RejekiNomplokCoupon::select('rejeki_nomplok_coupons.*' , DB::raw("(CASE WHEN coupon_number = '1' THEN 'Admin' WHEN user_group='2' THEN 'User' ELSE 'Superadmin' END) as name"))->with(['user', 'week', 'order', 'product']);
                $last_number = CouponsRejekiNomplok::getLastNumberIhsg($request->ihsg);
                $coupons->where('coupon_number' , $last_number);
            }else{
                $coupons = RejekiNomplokCoupon::select('rejeki_nomplok_coupons.*' , DB::raw("winners"))->with(['user', 'week', 'order', 'product']);
            }
            $coupons->where('rejeki_nomplok_week_id' , $week->id)->orderByDesc('created_at');
        }
        return DataTables::of($coupons->get())->addIndexColumn()->make(true);
    }

    public function products_create_view(Request $request) {
        $id = Auth::user()->id;
        $rejeki_nomplok_product_id = RejekiNomplokProduct::where('status', 'active')->pluck('product_id')->all();
        $products = Product::with('brand')->with('category')->where('status', 'active')->whereNotIn('id', $rejeki_nomplok_product_id)->orderBy('name')->get();
        // dd($products);
        return view('admin.events.rejeki-nomplok.products.create', compact('id', 'products'));
    }

    public function products_edit_view(Request $request) {
        $id = Auth::user()->id;
        $rejeki_id = $request->id;
        $rejeki = RejekiNomplokProduct::where('id', $rejeki_id)->first();
       // print_r($rejeki); exit;
        $products = Product::with('brand')->with('category')->where('status', 'active')->orderBy('name')->get();

        return view('admin.events.rejeki-nomplok.products.edit', compact('id', 'rejeki', 'rejeki_id', 'products'));
    }

    public function products_submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'product_id' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        $product = RejekiNomplokProduct::create([
            'product_id' => $request->product_id,
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        if ($product->first()) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function products_edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'product_id' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        $product = RejekiNomplokProduct::where('id', $request->id)->first();

        $product->product_id = $request->product_id;
        $product->status = $request->status;
        $product->updated_at = Carbon::now();
        $product->updated_by = $user->name;

        if ($product->save()) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function master_create_view(Request $request) {
        $id = Auth::user()->id;

        return view('admin.events.rejeki-nomplok.master.create', compact('id'));
    }

    public function master_edit_view(Request $request) {
        $id = Auth::user()->id;
        $master_id = $request->id;
        $master = RejekiNomplokWeek::where('id', $master_id)->first();
        
        $editable = true;
        $info = [];
        $coupon_winner = 'x';
        if($master){
            if($master->ihsg != null){
                $editable = false;
                $coupon_winner = CouponsRejekiNomplok::getLastNumberIhsg($master->ihsg);
                $info = CouponsRejekiNomplok::InfoTotalPaybackAndTotalWinnerByWeekId($master->id , $master->ihsg);
            }
        }
        return view('admin.events.rejeki-nomplok.master.edit', compact('id', 'master_id', 'master' ,'editable', 'info' ,'coupon_winner'));
    }

    public function master_banner(Request $request)
    {
        $user = Auth::user();
        $rejeki_nomplok = RejekiNomplokBanner::where('id',1)->first();
        $b_dekstop = $rejeki_nomplok->banner;
        // $b_mobile = $rejeki_nomplok->banner_mobile;

        if ($request->hasFile('banner')) {
            $file_name = uniqid();
            $file = $request->banner;
            $filename = $file_name .'-rejeki-nomplok-banner' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/rejeki-nomplok/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else {
            $upload = $b_dekstop;
        }

        // if ($request->hasFile('banner_mobile')) {
        //     $file_name = uniqid();
        //     $file = $request->banner_mobile;
        //     $filename = $file_name .'-rejeki-nomplok-banner-mobile' .time(). '.' . $file->getClientOriginalExtension();
        //     $destinationPath = '/upload/rejeki-nomplok/';
        //     $uploadBanner = Utils::upload_image($destinationPath, $file, $filename);
        //     if (!$uploadBanner) {
        //         return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
        //     }
        // } else {
        //     $uploadBanner = $b_mobile;
        // }

        $banner = RejekiNomplokBanner::where('id', 1)->update([
            'banner' => $upload,
            // 'banner_mobile' => $uploadBanner,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if ($banner > 0) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function master_submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'week' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        $master = RejekiNomplokWeek::create([
            'week' => $request->week,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        if ($master->first()) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function master_edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'ihsg' => 'required',
            'week' => 'required',
        ]);
       
        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        $week = RejekiNomplokWeek::where('week' , $request->week)->where('status' ,'active')->first();
        if(!$week){
            return json_encode(['status' => false, 'message' => 'Week not found']);
        }
        $week->ihsg = $request->ihsg;

        try {
            DB::beginTransaction();
            $week->save();
            // PROCESS COUPON
            $last_number = CouponsRejekiNomplok::getLastNumberIhsg($request->ihsg);
            // dd($last_number);
            // if(!$last_number){
            //     return json_encode(['status' => false, 'message' => 'Can\'t Process IHSG']);
            // }
            // UPDATE  WINNER
           // echo $week->id; exit;
            $winners = RejekiNomplokCoupon::where('rejeki_nomplok_week_id' , $week->id)->where('coupon_number' , $last_number)->get();
            // dd($winners);
            if($winners){
                foreach($winners as $value){
                    // PRICE
                   $price = $this->get_price($value->product_id , '' , $value->order_details_id);
                   RejekiNomplokCoupon::where('id' , $value->id)->update(
                       [
                           'is_winner' => true,
                           'value_transaction' => $price,
                           'status' => 'expired',
                       ]
                   );
                   Notify::send(
                        Notify::$rejeki_nomplok_winner_title, 
                        'Selamat, akun kamu terpilih sebagai pemenang Rejeki Nomplok' , 
                        '/kupon-rejeki', 
                        '', 
                        'uid', 
                        'Pemenang Rejeki Nomplok', 
                        $value->user_id
                    );
                }   
            }
            RejekiNomplokCoupon::where('rejeki_nomplok_week_id' , $week->id)->update(
                [
                    'status' => 'expired',
                    'updated_at' => Utils::now()
                ]
            );
            // $check_winner = RejekiNomplokCoupon::leftjoin('users', 'rejeki_nomplok_coupons.user_id', '=', 'users.id')
            //     ->select('name')
            //     ->where('rejeki_nomplok_week_id', $week->id)
            //     ->where('is_winner', true)
            //     ->get();

            // $name = "";
            // foreach($check_winner as $n => $win){
            //     if($n % 2 == 0){
            //         $name .= $win["name"] . ', ';
            //     } 
            //     else{
            //         $name .= $win["name"] . ' ';
            //     }
            // }
            // $text = RunningText::where('id', 1)->first();
            // if($text){
            //     RunningText::where('id', 1)->update([
            //         'text' => $name,
            //     ]);
            // } else {
            //     RunningText::create([
            //         'id' => 1,
            //         'text' => $name,
            //         'status' => 'active'
            //     ]);
            // }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return  $this->errorResponse(static::ORDER_ERROR_SAVE_DATA, $e->getMessage());
        }

        
        if(!$week->save()){
            return json_encode(['status' => false, 'message' => 'Can\'t Process IHSG']);
        }
        return json_encode(['status' => true, 'message' => 'Success to process Rejeki nomplok winner']);
    }

    public function get_price($product_id,$product_detail_id,$order_detail){
        $fixprice = OrderDetailProduct::select('order_detail_products.fix_price')
        ->leftJoin('product_details' , 'order_detail_products.product_detail_id' ,'product_details.id')
        ->leftJoin('products' , 'products.id' ,'product_details.product_id')
        ->where('products.id' ,  $product_id)
        //->where('product_details.id' ,  $product_detail_id)
        ->where('order_detail_products.order_detail_id' ,  $order_detail)->first();
        return $fixprice ? $fixprice->fix_price : 0;            
    }

    public function send_point(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }
        
        $payback = RejekiNomplokCoupon::where('id' , $request->id)->where('has_send_point', '!=' , true)->with('order.product')->with('product')->with('order')->first();
        //print_r($payback); exit;
        if(!$payback){
            return  json_encode(['status' => false, 'message' => 'Point sudah dikirim atau terjadi masalah, kontak admin']);
        }
        $fix_payback =  $this->get_product_rejecki_nomplok($payback->order_details_id,$payback->product->id , $payback->order->product->product_detail_id);


        $add_point = Hotpoint::send($payback->user_id, (int)($fix_payback), 'HOTE001', 'Kamu mendapat tambahan point dari invoice '.$payback->order->invoice_number.' pembelian produk '.$payback->product->name);
        if(!$add_point){
            return  json_encode(['status' => false, 'message' => 'Pengiriman poin gagal']);
        }
        // update Coupons data
        $payback->has_send_point = true;
        $payback->point_sent = $fix_payback;
        $payback->save();
        if($payback->save()){
            Notify::send(
                Notify::$rejeki_nomplok_winner_title, 
                'Selamat, hotdeal telah mengirimkan kamu hotpoint sebesan Rp. ' .(int)($fix_payback) . ' cek hotpoint kamu sekarang!' , 
                '/hot-point', 
                '', 
                'uid', 
                'Pemenang Rejeki Nomplok', 
                $payback->user_id
            );
        }
        
        return  json_encode(['status' => true, 'message' => 'Pengiriman poin berhasil']);
    }

    public function get_product_rejecki_nomplok($order_detail , $product_id , $product_detail_id){
        //return $order_detail.'-'.$product_id.'-'.$product_detail_id;
        $fixprice = OrderDetailProduct::select('order_detail_products.fix_price')->leftJoin('product_details' , 'order_detail_products.product_detail_id' ,'product_details.id')
                    ->leftJoin('products' , 'products.id' ,'product_details.product_id')
                    ->where('products.id' ,  $product_id)
                    ->where('product_details.id' ,  $product_detail_id)
                    ->where('order_detail_products.order_detail_id' ,  $order_detail)->first();
        return $fixprice ? $fixprice->fix_price : 0;            
    }

    public function running_text(){
        
        $text = RunningText::where('id', 1)->first();
        return view('admin.events.rejeki-nomplok.master.running-text', compact('text'));
    }

    public function save_running_text(Request $request){
        $validation = Validator::make($request->all(), [
            'text' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }
        $text = RunningText::where('id', 1)->first();

        $text->text = $request->text;
        $text->status = $request->status;
        $text->save();

        if ($text->save()) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }

    }
}
