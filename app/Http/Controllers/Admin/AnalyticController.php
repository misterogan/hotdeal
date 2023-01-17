<?php

namespace App\Http\Controllers\Admin;

use App\Cart;
use App\Exports\HotpointExcel;
use App\Exports\UserAnalyticExcel;
use App\Helpers\Utils;
use App\Hotpoint;
use App\Http\Controllers\Controller;
use App\Nru;
use App\Order;
use App\OrderDetail;
use App\OrderDetailLog;
use App\OrderDetailProduct;
use App\OrderVouchers;
use App\Product;
use App\ProductDetail;
use App\PromotionVoucher;
use App\User;
use App\UserAddress;
use App\ViewUserAgeRange;
use App\Wishlist;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AnalyticController extends Controller
{
    public function hot_point_index() {
        return view('admin.analytic.hot-point');
    }

    public function hotpoint_dt(){
        $hotpoints = Hotpoint::select(DB::raw('DATE(created_at) as date'))
                    ->selectRaw("SUM(CASE WHEN type = 'use' THEN value ELSE 0 END) AS sum_use")
                    ->selectRaw("SUM(CASE WHEN type = 'earn' THEN value ELSE 0 END) AS sum_earn")
                    ->groupBy('date')
                    ->orderBy('date', 'desc');
        // dd($hotpoints);
        return DataTables::of($hotpoints->get())->addIndexColumn()->make(true);
    }

    public function hot_point(Request $request){
        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(30)->startOfWeek()->format('Y-m-d');
        $weekStartMonth = $now->subMonths(12)->startOfMonth()->format('Y-m');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;
        $periods = CarbonPeriod::create($date_from, $date_until);


        $timestap = [];
        $earn_point = [];
        $use_point = [];
        $type1 = [];
        $type2 = [];
        $type3 = [];
        $type4 = [];

        foreach($periods as $period) {
            array_push($timestap,$period->format('Y-m-d'));
            $earn = Hotpoint::where('type', 'earn')
                ->whereDate('created_at',$period->format('Y-m-d'))
                ->sum('value');
            array_push($earn_point,$earn);
            $use = Hotpoint::where('type', 'use')
                ->whereDate('created_at',$period->format('Y-m-d'))
                ->sum('value'); 
            array_push($use_point,$use);
            $code1 = Hotpoint::where('code', 'EFM001')
                ->whereDate('created_at', $period->format('Y-m-d'))
                ->sum('value');
            array_push($type1,$code1);
            $code2 = Hotpoint::where('code', 'UTR001')
                ->whereDate('created_at', $period->format('Y-m-d'))
                ->sum('value');
            array_push($type2,$code2);
            $code3 = Hotpoint::where('code', 'EFC001')
                ->whereDate('created_at', $period->format('Y-m-d'))
                ->sum('value');
            array_push($type3,$code3);
            $code4 = Hotpoint::where('code', 'EFE001')
                ->whereDate('created_at', $period->format('Y-m-d'))
                ->sum('value');
            array_push($type4,$code4);
        }

        $months = array();
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::today()->startOfMonth()->subMonth($i);
                array_push($months, $month);
            }

        $earn_point_m = [];
        $use_point_m = [];
        $type1_m = [];
        $type2_m = [];
        $type3_m = [];
        $type4_m = [];
        foreach ($months as $per) {
            $month_data[] = $per->format('M-Y');
            $earn_m = Hotpoint::where('type', 'earn')
                ->whereMonth('created_at',$per->format('m'))
                ->whereYear('created_at', $per->format('Y'))
                ->sum('value');
            array_push($earn_point_m,$earn_m);
            $use_m = Hotpoint::where('type', 'use')
                ->whereMonth('created_at',$per->format('m'))
                ->whereYear('created_at', $per->format('Y'))
                ->sum('value'); 
            array_push($use_point_m,$use_m);
            $code1_m = Hotpoint::where('code', 'EFM001')
                ->whereMonth('created_at',$per->format('m'))
                ->whereYear('created_at', $per->format('Y'))
                ->sum('value');
            array_push($type1_m,$code1_m);
            $code2_m = Hotpoint::where('code', 'UTR001')
                ->whereMonth('created_at',$per->format('m'))
                ->whereYear('created_at', $per->format('Y'))
                ->sum('value');
            array_push($type2_m,$code2_m);
            $code3_m = Hotpoint::where('code', 'EFC001')
                ->whereMonth('created_at',$per->format('m'))
                ->whereYear('created_at', $per->format('Y'))
                ->sum('value');
            array_push($type3_m,$code3_m);
            $code4_m = Hotpoint::where('code', 'EFC001')
                ->whereMonth('created_at',$per->format('m'))
                ->whereYear('created_at', $per->format('Y'))
                ->sum('value');
            array_push($type4_m,$code4_m);
        }

        $data['earn'] = $earn_point;
        $data['use'] = $use_point;
        $data['type1'] = $type1;
        $data['type2'] = $type2;
        $data['type3'] = $type3;
        $data['type4'] = $type4;
        $data['timestamp'] = $timestap;
        
        //analytic by month
        $data['months'] = $month_data;
        $data['earn_by_month'] = $earn_point_m;
        $data['use_by_month'] = $use_point_m;
        $data['type1_by_month'] = $type1_m;
        $data['type2_by_month'] = $type2_m;
        $data['type3_by_month'] = $type3_m;
        $data['type4_by_month'] = $type4_m;

        return response()->json($data);
    }

    public function user_bio(Request $request){

        $location = UserAddress::selectRaw('cities.name as city_name,count(user_addresses.*) as total')
            ->leftJoin('cities','cities.id','=','user_addresses.city_id')
            ->groupBy('cities.name')->orderBy('total','DESC')->get();
        return view('admin.analytic.user_bio',compact('location'));
    }

    public function get_user_bio(Request $request){
        $data = User::selectRaw('gender,count(*) as total')->groupBy('gender')->get();
        $male = User::where('gender','male')->count();
        $female = User::where('gender','female')->count();
        $blank = User::whereNUll('gender')->count();

        $array['male'] = $male;
        $array['female'] = $female;
        $array['blank'] = $blank;

        return response()->json($array);
    }

    public function status_transaksi(Request $request){
        $awaiting = OrderDetail::where('status', 1)->count();
        $pending = OrderDetail::where('status', 2)->count();
        $processed = OrderDetail::where('status', 3)->count();
        $delivery = OrderDetail::where('status', 4)->count();
        $arrived = OrderDetail::where('status', 12)->count();
        $completed = OrderDetail::where('status', 5)->count();
        $cancel = OrderDetail::where('status', 8)->orWhere('status', 9)->orWhere('status', 10)->count();

        return view('admin.analytic.status-transaksi', compact('awaiting', 'pending', 'processed', 'delivery', 'arrived', 'completed', 'cancel'));
    }

    public function transaksi_dt(Request $request){
        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(30)->startOfWeek()->format('Y-m-d');
        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;

        $data = OrderDetail::select(DB::raw('
                    DATE(order_detail_products.created_at) as date,
                    COUNT(order_details.invoice_number) as total_invoice,
                    SUM(invoice_total_payment) as gmv,
                    SUM(invoice_total_payment) - SUM(order_detail_products.price * order_detail_products.quantity) as gross_profit,
                    ROUND((SUM(invoice_total_payment) - SUM(order_detail_products.price * order_detail_products.quantity) - 5000 ) / 1.11) as nmv
                '))
                ->join('order_detail_products', 'order_details.id', '=', 'order_detail_products.order_detail_id')
                ->whereIn('order_details.status', [5, 12])
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function transaksi_graph(Request $request){
        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(30)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;
        $periods = CarbonPeriod::create($date_from, $date_until);

        $timestap = [];
        $gmv = array();
        $nmv = array();
        $gross_profit = array();
        foreach($periods as $period) {
            $date = $period->format('Y-m-d');
            array_push($timestap,  $date);
            $detail = OrderDetail::select(DB::raw('
                DATE(order_detail_products.created_at) as date,
                SUM(invoice_total_payment) as gmv,
                SUM(invoice_total_payment) - SUM(order_detail_products.price * order_detail_products.quantity) as gross_profit,
                ROUND((SUM(invoice_total_payment) - SUM(order_detail_products.price * order_detail_products.quantity) - 5000 ) / 1.11) as nmv
                '))
                ->join('order_detail_products', 'order_details.id', '=', 'order_detail_products.order_detail_id')
                ->groupBy('date')
                ->whereDate('order_details.created_at', '=', $date)
                ->whereIn('order_details.status', [5, 12]);
            array_push($gmv, $detail->value('gmv') == null ? 0 : $detail->value('gmv'));
            array_push($nmv, $detail->value('nmv') == null ? 0 : $detail->value('nmv'));
            array_push($gross_profit, $detail->value('gross_profit') == null ? 0 : $detail->value('gross_profit'));
        } 
        
        $data['timestamp'] = $timestap;
        $data['gmv'] = $gmv;
        $data['nmv'] = $nmv;
        $data['gross_profit'] = $gross_profit;
        return response()->json($data);
    }


    public function product(Request $request){
        $products = Product::get();
        return view('admin.analytic.product',compact('products'));
    }

    public function get_data_product(Request $request){

        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(7)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;

        $period = CarbonPeriod::create($date_from, $date_until);
        $timestamp = array();
        $sum = array();

        foreach ($period as $date) {

            $date = $date->format('Y-m-d');
            if($request->product_name == 'all'){
                $products = OrderDetailProduct::whereDate('created_at','=',$date)->sum('quantity');
            }else{
                $products = OrderDetailProduct::
                leftJoin('product_details','product_details.id','=','order_detail_products.product_detail_id')
                    ->leftJoin('products','products.id','=','product_details.product_id')
                    ->where('products.id',$request->product_name)
                    ->whereDate('order_detail_products.created_at','=',$date)->sum('quantity');
            }


            array_push($timestamp,$date);
            array_push($sum,$products);
        }

        $data['timestamp'] = $timestamp;
        $data['sum'] = $sum;
        return response()->json($data);
    }


    public function get_dt(Request $request) {

        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(7)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;


        $products = OrderDetailProduct::selectRaw('products.name as product_name,count(order_detail_products.*) as total')
            ->leftJoin('product_details','product_details.id','=','order_detail_products.product_detail_id')
            ->leftJoin('products','products.id','=','product_details.product_id')
            ->whereNotNull('products.name')
            ->where('order_detail_products.created_at','>=',$date_from)
            ->where('order_detail_products.created_at','<=',$date_until)
            ->groupBy('product_name')
            ->orderBy('total','DESC')
            ->skip(0)
            ->take(10);


        return DataTables::of($products->get())->addIndexColumn()->make(true);
    }

    public function nru(Request $request){
        $source = collect(User::selectRaw('registration_source as label , count(*) as total')->where('is_vendor' , false)->groupBy('registration_source')->get())->pluck('total','label');
        $phone = collect(User::selectRaw('is_phone_verified as label , count(*) as total')->where('is_vendor' , false)->groupBy('is_phone_verified')->get())->pluck('total','label');
        $status = collect(User::selectRaw('status as label , count(*) as total')->where('is_vendor' , false)->groupBy('status')->get())->pluck('total','label');
        $data['source'] = [
            'data' => $source,
            'colour' => Utils::randomHex(count($source))
        ];
        $data['phone'] = [
            'data' => $phone,
            'colour' => Utils::randomHex(count($phone))
        ];
        $data['status'] = [
            'data' => $status,
            'colour' => Utils::randomHex(count($status))
        ];
        $datas = json_encode($data);
        //echo json_encode($data); exit;
        return view('admin.analytic.nru',compact('datas'));
    }
    
    public function cart_wishlist_index(){
        return view('admin.analytic.cart-wishlist');
    }

    public function cart_wishlist(Request $request){
        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(30)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;
        $periods = CarbonPeriod::create($date_from, $date_until);

        $products = Cart::select("products.name")
        ->join("product_details","product_details.id","=","cart.product_details_id")
        ->join("products", "products.id", "=", "product_details.product_id")
        ->orderByRaw("COUNT(products.name) desc")
        ->groupBy("products.name")
        ->where('cart.status', 'active')
        ->take(20) # or how many cart you want
        ->pluck('products.name');

        $timestap = [];
        $cartData = [];
        $wishlistData = [];
        $topProductCartData = [];
        $productData = [];
        $cartProductData = [];
        $wishlistProductData = [];

        foreach($periods as $period) {
            array_push($timestap,$period->format('Y-m-d'));
            $cart = Cart::where('status', 'active')
                ->whereDate('updated_at',$period->format('Y-m-d'))
                ->count();
            array_push($cartData,$cart);
            $wishlist = Wishlist::where('status', 'active')
                ->whereDate('updated_at',$period->format('Y-m-d'))
                ->count();
            array_push($wishlistData,$wishlist);
            $topProductCart = Cart::select("products.name")
                ->join("product_details","product_details.id","=","cart.product_details_id")
                ->join("products", "products.id", "=", "product_details.product_id")
                ->orderByRaw("COUNT(products.name) desc")
                ->groupBy("products.name")
                ->whereDate('cart.updated_at', $period->format('Y-m-d'))
                ->where('cart.status', 'active')
                ->take(5) # or how many cart you want
                ->pluck('products.name');
            array_push($topProductCartData,$topProductCart);
        } 

        foreach ($products as $product) {
            array_push($productData,$product);
            $cartProduct = Cart::select("products.name")
                ->join("product_details","product_details.id","=","cart.product_details_id")
                ->join("products", "products.id", "=", "product_details.product_id")
                ->orderByRaw("COUNT(products.name) desc")
                ->where('cart.status', 'active')
                ->where('products.name', $product)
                ->count();
            array_push($cartProductData,$cartProduct);
            $wishlistProduct = Wishlist::select("products.name")
                ->join("products", "products.id", "=", "wishlist.product_id")
                ->orderByRaw("COUNT(products.name) desc")
                ->where('wishlist.status', 'active')
                ->where('products.name', $product)
                ->count();
            array_push($wishlistProductData,$wishlistProduct);
        }

        $data['cart'] = $cartData;
        $data['wishlist'] = $wishlistData;
        $data['cartProduct'] = $topProductCartData;
        $data['timestamp'] = $timestap;
        $data['products'] = $productData;
        $data['cartProducts'] = $cartProductData;
        $data['wishlistProducts'] = $wishlistProductData;
        return response()->json($data);
    }

    public function voucher_index(){
        $voucher = PromotionVoucher::with('orderVoucher')->get();
        return view('admin.analytic.voucher', compact('voucher'));
    }

    public function voucher(Request $request){

        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(30)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;
        $periods = CarbonPeriod::create($date_from, $date_until);

        $timestap = [];
        $voucherTotal = [];
        $dataVoucher = [];
        $single_voucher = [];

        $filter_voucher = $request->filter_voucher;
        if($filter_voucher != ''){
            foreach($periods as $period) {
                array_push($timestap,$period->format('Y-m-d'));
                $voucher = PromotionVoucher::leftjoin('order_vouchers', 'promotion_vouchers.id', 'order_vouchers.voucher_id')
                            ->where('promotion_vouchers.voucher_name', $filter_voucher)
                            ->whereDate('order_vouchers.created_at',$period->format('Y-m-d'))->count();
                array_push($single_voucher,$voucher);
            }
            $voucher_name = PromotionVoucher::where('voucher_name', $filter_voucher)->first();
            $data['voucher'] = $voucher_name->voucher_name;
            $data['data'] = $single_voucher;
            $data['timestamp'] = $timestap;
            // dd($filter_voucher, $data);
            return response()->json($data);

        } else{
            $voucherId = OrderVouchers::select('voucher_id')->whereBetween('created_at', [$date_from, $date_until])->groupBy('voucher_id')->pluck('voucher_id');
            foreach($periods as $period) {
                array_push($timestap,$period->format('Y-m-d'));
                $voucher = OrderVouchers::selectRaw('voucher_id, count(voucher_id) as totalVoucher')
                            ->with('voucher')
                            ->whereDate('created_at',$period->format('Y-m-d'))
                            ->groupBy('voucher_id')->get();
                foreach($voucherId as $id){
                    if(!in_array($id, $voucher->pluck('voucher_id')->toArray())){
                        $dataVoucher[$id]['data'][] = 0;
                    }
                }
                foreach($voucher as $v){
                    $dataVoucher[$v->voucher_id]['name'] =  $v->voucher->voucher_name;
                    $dataVoucher[$v->voucher_id]['data'][] = $v->totalvoucher;
                }
                // $voucherTotal[] = $voucher;
                array_push($voucherTotal, $voucher);
            }
            $data['data'] = $voucherTotal;
            $dataVoucher['timestamp'] = $timestap;
            // dd($dataVoucher);
            return response()->json($dataVoucher);
        }
    }

    public function get_user_bio_dob(Request $request){

        $age_group = array();
        $age_count = array();
        $age_range = ViewUserAgeRange::get();

        foreach ($age_range as $val){
            array_push($age_group,$val->age_group);
            array_push($age_count,$val->age_count);
        }

        $data['age_group'] = $age_group;
        $data['age_count'] = $age_count;

        return response()->json($data);
    }

    public function all_user(){
        $user = Nru::select(DB::raw('count(*) as total_user') , DB::raw("to_char(nrus.created_at::DATE , 'Mon' ) as month"))
        ->where('nrus.created_at' ,'>' , Carbon::now()->subMonth(11)->startOfMonth())
        ->groupBy(DB::raw("to_char(nrus.created_at::DATE , 'Mon' )"))->orderBy(DB::raw("to_char(nrus.created_at::DATE , 'Mon' )") , "ASC")->pluck('total_user','month')->toArray();
        //print_r($user);
        // exit;
        $user_yoy = [];
        for ($i = 11; $i >= 0; --$i) {
            // echo $i;
            $dt = Carbon::now()->startOfMonth()->subMonth($i);
           
            if(array_key_exists($dt->format('M') , $user)){
                //echo $dt->format('M');
                $user_yoy[$dt->format('M')] = (int)($user[$dt->format('M')]);
            }else{
                $user_yoy[$dt->format('M')] = 0;
            }
        }
        return json_encode($user_yoy);
    }

    public function utm(){
        return view('admin.analytic.cart-wishlist');
    }

    public function download(Request $request){
        return Excel::download(new UserAnalyticExcel, 'user-bio-'.date('y-m-d').'.xlsx');
    }

    public function download_hotpoint(){
        return Excel::download(new HotpointExcel, 'hot-point-'.date('y-m-d').'.xlsx');
    }
}
