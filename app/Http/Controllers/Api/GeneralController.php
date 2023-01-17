<?php

namespace App\Http\Controllers\Api;

use App\AboutUs;
use App\Banner;
use App\FAQ;
use App\Helpers\Emails;
use App\Helpers\OTP;
use App\HighlightProduct;
use App\HighlightTitle;
use App\Http\Resources\CustomerProductsResource;
use App\Http\Resources\ProductShortResource;
use App\Newsletter;
use App\Notification;
use App\Order;
use App\OrderDetail;
use App\Page;
use App\Vendor;
use App\PasswordReset;
use App\Privacy;
use App\Product;
use App\Strength;
use App\VendorBanner;
use App\ViewProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Api
{
    /**
     * @OA\Get(
     * path="/api/about-us/get",
     * summary="About Us",
     * description="get About us",
     * operationId="get About us",
     * tags={"About-us"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_about_us(Request $request)
    {
        $about_us = AboutUs::where('status','active')->first();

        return $this->successResponse($about_us);
    }

    /**
     * @OA\Get(
     * path="/api/faqs/get",
     * summary="Get FAQs",
     * description="get faqs",
     * operationId="get faqs",
     * tags={"Faqs"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_faqs(Request $request)
    {
        $faqs = FAQ::where('status','active')->orderBy('title', 'asc')->get();
        $faq = [];
        $menu = [];
        foreach($faqs as $item){
            $faq[str_replace(' ' , '_' ,$item->title)][] = $item;
            if(!array_key_exists(str_replace(' ' , '_' ,$item->title), $menu) ){
                $menu[str_replace(' ' , '_' ,$item->title)] = $item->title;
            }
        }
        $response = [
            'menu' => $menu,
            'faqs' => $faq
        ];
        return $this->successResponse($response);
    }

    public function get_privacy(Request $request)
    {
        $privacy = Privacy::where('status','active')->get();
        $policy = [];
        $menu = [];
        foreach($privacy as $item){
            $policy[str_replace(' ', '_', $item->title)][] = $item;
            if(!array_key_exists(str_replace(' ', '_', $item->title), $menu) ){
                $menu[str_replace(' ', '_', $item->title)] = $item->title;
            }
        }

        $response = [
            'menu' => $menu,
            'privacy' => $policy
        ];
        return $this->successResponse($response);

    }

    public function privacy(Request $request, $slug)
    {
        $privacy = Privacy::where('status','active')->where('slug', $slug)->first();

        return $this->successResponse($privacy);
    }

    public function page()
    {
        $page = Page::all();

        return $this->successResponse($page);
    }

    public function news_detail(Request $request){
        $page = Page::where('status', 'active')->where('slug' , $request->slug)->first();
        if($page){
            $data = $page;
            return $this->successResponse($data);
        }
        return $this->errorResponse('Data Not Found' , 201);
    }

    /**
     * @OA\Get(
     * path="/api/strengths/get",
     * summary="Hotdeal Strengths (above footer)",
     * description="get Strengths",
     * operationId="get Strengths",
     * tags={"Strengths"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_strengths(Request $request)
    {
        $strengths = Strength::where('status', 'active')->orderBy('id', 'desc')->get();

        return $this->successResponse($strengths);
    }

    public function newsletter(Request $request) {

        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse('Silahkan Masukkan Email Terlebih Dahulu',201);
        }

        $newsletter = Newsletter::updateorcreate([
            'email' => $request->email
        ],[
            'email' => $request->email,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($newsletter->first()) {
            return $this->successResponse($newsletter, 'Kamu Sudah Berlangganan Email Di Hotdeal, Nantikan Promo-Promo Dari Kami');
        } else {
            return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE, 502);
        }
    }

    public function headermaterials(){
        $data['notification'] = 0;
        if(!Auth::check()){
            $this->successResponse([]);
        }
        $data['notification'] = Notification::where('user_id', Auth::user()->id)->where('is_read', false)->count();
        return $this->successResponse($data);
    }

    public function get_vendor() {
        return $this->vendor();
    }

    public function user_total_payment(){
        $user = Auth::user();
        $order = OrderDetail::leftjoin('orders', 'order_details.order_id', '=', 'orders.id')->where('order_details.status', 5)->where('user_id', $user->id)->count();
        
        // dd( $order);
        return $this->successResponse($order);
    }

    public function get_highlight_title(){
        $title = HighlightTitle::all();
        $data = [
            'section_1' => $title->where('section', 1)->first(),
            'section_2' => $title->where('section', 2)->first(),
            'section_3' => $title->where('section', 3)->first(),
        ];
        return $this->successResponse($data);
    }

    public function forget_pin_send_otp(){
        $user = Auth::user();
        $otp = OTP::otp_number();
        $email = Emails::send_email($user->name, $user->email, 'Forget Pin Verification', 'Your OTP verification code '.$otp);
        if ($email) {
            PasswordReset::create([
                'email' => $user->email,
                'token' => $otp,
                'created_at' => Carbon::now(),
            ]);
        }
        return $this->successResponse($email, "success", 200);
    }

    public function forget_pin_check_otp(Request $request){
        $user = Auth::user();
        $verify = PasswordReset::where('email', $user->email)->where('token', $request->otp)->first();
        if ($verify) {
            // check OTP expiry
            $expired = date($verify->created_at, strtotime("+5 min"));
            if ($expired > Carbon::now()) {
                return json_encode(['status'=> false, 'message'=> "Kode telah Expired"]);
            }
            return $this->successResponse($verify, "success", self::SUCCESS_VERIFY_PIN);
        } else{
            return $this->errorResponse('OTP Salah!', self::ERROR_VERIFY_PIN);
        }
    }

    public function merchant_banner(Request $request) {
        $vendor_id = Vendor::select('id')->where('status' , 'active')->where('slug' , $request->vendor_id)->first();
        $banner = VendorBanner::where('vendor_id', $vendor_id->id)->where('status', 'active')->orderBy('id')->get();
        if($banner) {
            return $this->successResponse($banner, "success", 200);
        }
        return $this->errorResponse('OTP Salah!', 'error');
    }

    public function merchant_highlight_product(Request $request) {
       // $vendor_id = $request->vendor_id;
        $vendor_id = Vendor::select('id')->where('status' , 'active')->where('slug' , $request->vendor_id)->first();
        $products = ViewProduct::select('products.id as product_id','products.name','products.slug')
                    ->join('products', 'view_products.product_id', 'products.id')
                    ->join('highlight_products', 'products.id', 'highlight_products.product_id')
                    ->where('products.vendor_id', $vendor_id->id)
                    ->where('highlight_products.highlight_type', 6)
                    ->where('products.status', 'active')
                    ->where('highlight_products.status', 'active')
                    ->orderby('highlight_products.sequence')
                    ->groupBy('products.id', 'highlight_products.sequence')
                    ->limit(12)
                    ->get();
        $data = [
                'products' => ProductShortResource::collection($products)
            ];
        return $this->successResponse($data);
    }
}
