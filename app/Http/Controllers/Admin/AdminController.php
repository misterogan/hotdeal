<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Area;
use App\Backlink;
use App\Cities;
use App\Coupon;
use App\Dau;
use App\FileUpload;
use App\Helpers\Emails;
use App\Helpers\Hotpoint;
use App\Helpers\Invite;
use App\Helpers\OrderDetails;
use App\Helpers\Notify;
use App\Helpers\RejekiNomplok;
use App\Helpers\Utils;
use App\HighlightTitle;
use App\Http\Controllers\Controller;
use App\Jobs\RaffleTicket;
use App\Notification;
use App\NotificationDetail;
use App\Jobs\TestQue;
use App\MediaLog;
use App\Order;
use App\OrderDetail;
use App\OrderDetailLog;
use App\OrderDetailProduct;
use App\OrderHistory;
use App\OrderLog;
use App\Product;
use App\ProductDetail;
use App\ProductPurchase;
use App\PromotionVoucher;
use App\Province;
use App\RejekiNomplokWeek;
use App\Review;
use App\ReviewGallery;
use App\Shipment;
use App\ShipmentService;
use App\Suburbs;
use App\TestingJob;
use App\User;
use App\UserActivity;
use App\Vendor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AdminController extends Controller
{
    public function index() {
        $sales = OrderDetail::select(DB::raw('SUM(order_details.invoice_total_payment + order_detail_shipping.shipping_cost) as total_sales') , DB::raw("to_char(order_details.created_at::DATE , 'Mon' ) as month"))
        ->leftJoin('order_detail_shipping' , 'order_detail_shipping.order_details_id', 'order_details.id')
        ->where('status' , 5)
        ->where('order_details.created_at' ,'>' , Carbon::now()->subMonth(11)->startOfMonth())
        ->groupBy(DB::raw("to_char(order_details.created_at::DATE , 'Mon' )"))->orderBy(DB::raw("to_char(order_details.created_at::DATE , 'Mon' )") , "ASC")->pluck('total_sales','month')->toArray();
        // print_r($sales);
        // exit;
        $sales_yoy = [];
        for ($i = 11; $i >= 0; --$i) {
            $dt = Carbon::now()->startOfMonth()->subMonth($i);
            if(array_key_exists($dt->format('M') , $sales)){
                $sales_yoy[$dt->format('M')] = (int)($sales[$dt->format('M')]);
            }else{
                $sales_yoy[$dt->format('M')] = 0;
            }
        }

        $now = Carbon::now();
        $today =  $now->toDateString();

        $new_registered = DB::table('nrus')
            ->whereDate('created_at',$today)
            ->count();

        $total_users = User::count();
        $transaction_today = Order::whereDate('created_at',$today)->where('status',5)->sum('total_payment');
        $total_transactions = Order::where('status',5)->sum('total_payment');
        $orders = Order::with('order_detail')->orderByDesc('created_at')->get();
        $products = Product::select('products.*','product_galleries.link','vendors.name as vendor_name','product_details.price')
            ->leftJoin('product_galleries','product_galleries.product_id','=','products.id')
            ->leftJoin('product_details','product_details.product_id','=','products.id')
            ->leftJoin('vendors','vendors.id','=','products.vendor_id')
            ->where('products.status','active')->orderBy('products.created_at','desc')
            ->limit(5)
            ->offset(5)
            ->get();
        return view('admin.dashboard', compact('orders','new_registered','total_users','transaction_today','total_transactions','products' , 'sales_yoy'));
    }

    public function user_select2() {
        $users = User::orderBy('id')->get();

        return json_encode($users);
    }

    public function city_select2(Request $request) {
        $states = Cities::where("province_id",$request->province_id)
            ->pluck("name","id");
        return response()->json($states);
    }

    public function suburb_select2(Request $request) {
        $suburbs = Suburbs::where("city_id",$request->city_id)
            ->pluck("name","id");
        return response()->json($suburbs);
    }

    public function area_select2(Request $request) {
        $area = Area::where("suburb_id",$request->suburb_id)
            ->pluck("name","id");
        return response()->json($area);
    }

    public function notification(){
        $notifications = NotificationDetail::select('notifications.url as url', 'notifications.body as body', 'notifications.title as title')
                         ->join('notifications', 'notification_details.notification_id', '=', 'notifications.id')
                         ->where('notification_details.user_id', '0')
                         ->orderBy('notifications.id', 'desc')->get();
        // dd($notifications);
        return view('admin.notification', compact('notifications'));
    }

    // This is for testing purposes
    public function send_email() {
        Emails::send_email('Kervin C.', 'kervin@cashtree.id', 'Test Email', '12345678');
    }

    public function chart(Request $request){

        $now = Carbon::now();
        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(7)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_until) ? $request->date_until : $today;
        $periods = CarbonPeriod::create($date_from, $date_until);

        $timestap = [];
        $dau = [];
        $nru = [];

        foreach($periods as $period) {

            array_push($timestap,$period->format('Y-m-d'));
            $daily_active_user = DB::table('dau')
                ->whereDate('created_at',$period->format('Y-m-d'))
                ->count();
            array_push($dau,$daily_active_user);
            $new_register_user = DB::table('nrus')
                ->whereDate('created_at',$period->format('Y-m-d'))
                ->count();
            array_push($nru,$new_register_user);

        }

        $data['dau'] = $dau;
        $data['nru'] = $nru;
        $data['timestamp'] = $timestap;
        return response()->json($data);
    }

    // public function job_test(){}

    public function test(){
        // dd( \Illuminate\Http\Testing\MimeType::from('test.jpg') );
        // $dummy = \Illuminate\Http\Testing\File::from('test.jpg');
        $dummy = UploadedFile::fake()->create('text.png');;
        if ($dummy) {
            $file_name = uniqid();
            $file = $dummy;
            $filename = $file_name . '-banners' . time() . '.' . $dummy->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        }

        dd($img);
        // $week = substr(RejekiNomplokWeek::where('status', 'active')->orderByDesc('id')->pluck('week')->first(), -2) + 1;
        // return (int)(date('Y').$week);
        // $week = (int)(RejekiNomplokWeek::where('status', 'active')->orderByDesc('id')->pluck('week')->first()) + 1;
        // return $week;
    }

    // public function test(){
    //     $date = Carbon::now()->subDays(3);

    //     DB::beginTransaction();
    //     DB::enableQueryLog();
    //     try {
            
    //         $detail = OrderDetail::select('id', 'status')->where('status', 12)
    //             ->where(DB::raw('DATE(updated_at)'), '<=', $date->toDateString())->get();
                
    //             foreach ($detail as $log) {
    //             $order = OrderDetail::where('id', $log->id)->with('order')->first();
    //             $coupon = RejekiNomplok::create_coupon($order->invoice_number, $order->order->user_id);
    //             if ($order) {
    //                 $order->status = 5;
    //                 $order->updated_at = Carbon::now();
    //                 $order->save();

    //                 OrderDetailLog::create([
    //                     'order_details_id' => $log->id,
    //                     'status' => 5,
    //                     'created_at' => Carbon::now(),
    //                     'updated_at' => Carbon::now(),
    //                     'created_by' => 'System'
    //                 ]);
    //             }
    //         }
    //         DB::commit();
    //     } catch (\Throwable $e) {
    //         Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => '']);
    //         DB::rollback();
    //         return false;
    //     }

    //     return "berhasil";
    // }

    public function google_analytic(){
        // dd("test");
        $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
        return $analyticsData;
    }
}
