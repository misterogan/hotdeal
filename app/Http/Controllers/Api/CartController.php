<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Helpers\Logistics;
use App\Helpers\Product;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartDetailResource;
use App\Http\Resources\CartResource;
use App\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CartController extends Api
{
    /**
     * @OA\Post(
     * path="/api/cart/insert",
     * summary="Cart",
     * description="insert Cart",
     * operationId="insert Cart",
     * tags={"Cart"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="pdid",
     *          required=true,
     *          in="query",
     *          description="product_detail_id",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="qty",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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

    public function insert(Request $request){
        // dd($request->all());
        $user = $this->user();
        $username = $user->name;
        $validation = Validator::make($request->all(), [
            'pdid' => 'required',
            'qty' => 'required',
        ]);
        $prod_id = $request->pdid;

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }
        $stock = ProductDetail::select('stock')->where('id' , $prod_id)->first();
        if($stock){
            if($stock->stock < $request->qty){
                return $this->errorResponse('Sisa stock untuk produk ini '.$stock->stock , 202);
            }
        }
        $item = Cart::where(['user_id'=>$user->id,'product_details_id'=>$prod_id])->first();

        if($item){

            DB::beginTransaction();
            try{
                $qty = isset($request->from_add_cart) ? (int)$item->quantity + (int)$request->qty :  (int)$request->qty;
                if($qty > $stock->stock){
                    return $this->errorResponse('Sisa stock untuk produk ini '.$stock->stock , 202);
                }
                if($request->cusQty == true){
                    $data_update = [
                        "status" => 'active',
                        "in_checkout" => $item->in_checkout,
                        "quantity" => isset($request->from_add_cart) ? (int)$item->quantity + (int)$request->qty :  (int)$request->qty,
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ];
                } else{
                    $data_update = [
                        "status" => 'active',
                        "in_checkout" => false,
                        "quantity" => isset($request->from_add_cart) ? (int)$item->quantity + (int)$request->qty :  (int)$request->qty,
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ];
                }
                if((int)$item->quantity == 0){
                    $data_update['created_at'] = date('Y-m-d H:i:s');
                }
                Cart::where([
                    ['user_id',$user->id],
                    ['product_details_id',$prod_id]
                ])->update
                ($data_update);

                DB::commit();
            }
            catch ( Throwable $e) {
                DB::rollback();
            }
        }else{

            DB::beginTransaction();
            try{
                Cart::create(
                    ([
                        "status" => 'active',
                        "in_checkout" => false,
                        "user_id" => $user->id,
                        "product_details_id" => $prod_id,
                        "quantity" => $request->qty,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ])
                );
                DB::commit();
            }catch (Throwable $e) {
                DB::rollback();
            }
        }
        $response['user'] = $username;
        $response['message'] = "berhasil" ;
        $response['count_cart'] =  Utils::number_for_badge(Cart::where(['user_id'=>$user->id,'status'=>'active'])->sum('quantity'));
        return $this->successResponse($response);
    }


    /**
     * @OA\Post(
     * path="/api/cart/buy/now",
     * summary="Cart",
     * description="Buy Now",
     * operationId="Buy Now",
     * tags={"Cart"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="pdid",
     *          required=true,
     *          in="query",
     *          description="product_detail_id",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="qty",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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

    public function buy_now(Request $request){
        $user = $this->user();
        // $item = Cart::where('user_id',$user->id)->update
        // ([
        //     "in_checkout" => false,
        // ]);
        // if(!$item){
        //     //return  $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);;
        // }
        
        $item = Cart::where(['user_id'=>$user->id,'product_details_id'=>$request->pdid])->first();

        $stock = ProductDetail::select('stock')->where('id' , $request->pdid)->first();
            
        if($stock){
            if($stock->stock < 1){
                return $this->errorResponse('Sisa stock untuk produk ini '.$stock->stock , 202);
            } 
            if($stock->stock < $request->qty){
                return $this->errorResponse('Sisa stock untuk produk ini '.$stock->stock , 202);
            }
        }

        if($item){
            DB::beginTransaction();
            try{
                if($stock){
                    if($stock->stock < $item->quantity){
                        return $this->errorResponse('Sisa stock untuk produk ini '.$stock->stock.', kamu sudah memiliki '.$item->quantity.' item dikeranjang.' , 202);
                    }
                }
                Cart::where([
                    ['user_id',$user->id],
                    ['product_details_id',$request->pdid]
                ])->update
                ([
                    "status" => 'active',
                    "in_checkout" => true,
                    "quantity" => isset($request->from_add_cart) ? (int)($item->quantity) + (int)($request->qty) :  (int)($request->qty),
                    "updated_at"=>date('Y-m-d H:i:s'),
                ]);
                DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
            }
        }else{
            
            DB::beginTransaction();
            try{
                Cart::create(
                    ([
                        "status" => 'active',
                        "user_id" => $user->id,
                        "product_details_id" => $request->pdid,
                        "in_checkout" => true,
                        "quantity" => $request->qty,
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ])
                );
                DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
            }
        }
        $response['message'] = "berhasil" ;
        $response['count_cart'] =  Utils::number_for_badge(Cart::where(['user_id'=>$user->id,'status'=>'active'])->sum('quantity'));
        return $this->successResponse($response);
    }



    /**
     * @OA\Post(
     * path="/api/cart/delete",
     * summary="Cart",
     * description="delete Cart",
     * operationId="delete Cart",
     * tags={"Cart"},
     *     @OA\Parameter(
     *          name="pdid",
     *          required=true,
     *          in="query",
     *          description="product_detail_id",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="status",
     *          required=true,
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

    public function delete(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'pdid' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        if($request->status != 'all'){
            // check ownership of all product id
            $item = Cart::select('product_details_id')->where(['user_id'=>$user->id])->wherein('product_details_id' , $request->pdid)->count();
            if($item != count($request->pdid)){
                return  $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);;
            }
        }
        if($request->status == 'all'){
            DB::beginTransaction();
            try{

                $response['status'] = "all" ;
                Cart::where('user_id',$user->id)->update
                ([
                    "status" => 'deleted',
                    "in_checkout" => false,
                    "quantity" => 0,
                    "updated_at"=>date('Y-m-d H:i:s'),
                ]);
                DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
                return $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);
            }
        }else{

            DB::beginTransaction();
            try{
                Cart::where('user_id',$user->id)->whereIn('product_details_id' , $request->pdid)->update
                    ([
                        "status" => 'deleted',
                        "in_checkout" => false,
                        "quantity" => 0,
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ]);
                DB::commit();
            }
            catch (Exception $e) {
                DB::rollback();
                return $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);
            }

        }
        $response['message'] = "berhasil" ;
        $response['count_cart'] =  Utils::number_for_badge(Cart::where(['user_id'=>$user->id,'status'=>'active'])->sum('quantity'));
        return $this->successResponse($response);
    }

    public function deleteVendor(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'data' => 'required',
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        DB::beginTransaction();
        try{
            foreach ($request->data['products'] as $key => $value) {
                Cart::where('user_id',$user->id)->where('product_details_id' , $value['product_details_id'])
                        ->update([
                            "status" => 'deleted',
                            "in_checkout" => false,
                            "quantity" => 0,
                            "updated_at"=>date('Y-m-d H:i:s'),
                        ]);
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);
        }
        $response['message'] = "berhasil" ;
        $response['count_cart'] =  Utils::number_for_badge(Cart::where(['user_id'=>$user->id,'status'=>'active'])->sum('quantity'));
        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     * path="/api/cart/get",
     * summary="cart",
     * description="get cart",
     * operationId="get cart",
     * tags={"Cart"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get(Request $request)
    {
        $user = $this->user();
        if($user){
            $user_id = $user->id;
            $cart = Cart::select('vendor_id', 'vendors.name', 'vendors.image', 'vendors.city_id')
                        ->leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
                        ->leftJoin('products' , function($join){
                            $join->on('product_details.product_id','=','products.id');
                            $join->where('products.status' , 'active');
                            $join->where('product_details.status' , 'active');
                            $join->whereNotNull('products.id');
                        })
                        ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
                        ->where('cart.status', 'active')
                        ->where('cart.user_id', $user_id)
                        ->whereNotNull('vendors.id')
                       
                        ->groupBy('products.vendor_id', 'vendors.name', 'vendors.image', 'vendors.city_id');
                        // ->get();

            $carts = $cart->get();
            $check_all = $cart->where('in_checkout', false)->first();
            
            $data = [
                'cart'=> CartDetailResource::collection($carts),
                'in_checkAll' => $check_all == null ? true : false
            ];
            return $this->successResponse($data);
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }

    }

     /**
         * @OA\Get(
         * path="/api/cart/mark/checkout",
         * summary="cart",
         * description="get banner-cart",
         * operationId="get banner-cart",
         * tags={"Cart"},
         * security={ {"sanctum": {} }},
         * @OA\Response(
         *    response=200,
         *    description="Successful Operation",
         *      @OA\JsonContent(

        *        )
        *     )
        * )
     */
    public function mark_as_checkout(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'pdid' => 'required',
            'status' => 'required',
            'is_all' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }
        if($user){
            if(isset($request['vendor_id'])){
                $cart = Cart::select('vendor_id', 'vendors.name', 'vendors.image', 'vendors.city_id', 'cart.in_checkout')
                        ->leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
                        ->leftJoin('products', 'product_details.product_id', 'products.id')
                        ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
                        ->where('cart.status', 'active')
                        ->where('cart.user_id', $user->id)
                        ->whereNotNull('product_details.id')
                        ->where('products.vendor_id', $request['vendor_id']);
                $cart->update([
                    'in_checkout' => $request->is_all
                ]);

                return $this->successResponse([]);
            }
            if($request->pdid != false ){
                $cart = Cart::where('user_id' , $user->id)->where('product_details_id' , $request->pdid)->first();
                if(!Product::stock($request->pdid , $cart->quantity)){
                    return $this->errorResponse(static::OUT_OF_STOCK,static::OUT_OF_STOCK_CODE);
                }
                if(!$cart){
                    return $this->errorResponse(static::PRODUCT_NOT_FOUND,static::PRODUCT_NOT_FOUND_CODE);
                }
                $cart->in_checkout = $request->status;
                if(!$cart->save()){
                    return $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);
                }
            }else{
                $cart = Cart::where('user_id' , $user->id)->update([
                    'in_checkout' => $request->is_all
                ]);
                if(!$cart){
                    return $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);
                }
            }
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
        $vendor = ProductDetail::where('product_details.id', $request->pdid)
                    ->join('products', 'product_details.product_id', 'products.id')
                    ->where('products.status', 'active')
                    ->where('product_details.status', 'active')
                    ->pluck('vendor_id')
                    ->first();
        $vendor_checkout = $this->vendor_checkout($vendor);
        return $this->successResponse($vendor_checkout);
    }
    public function checkout(){

    }

    public function save_note(Request $request){
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'pdid' => 'required',
            // 'note' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        $cart = Cart::where('product_details_id', $request->pdid)->where('user_id', $user->id)->where('status', 'active')->first();
        
        if($cart){
            $cart->notes = $request->note;
            if($cart->save()){
                return $this->successResponse($request->note);
            }
        }
        return $this->errorResponse('error', 204);
    }

    public function vendor_checkout($id){
        $user_id = Auth::user()->id;
        $checkout = Cart::leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
                ->leftJoin('products', 'product_details.product_id', 'products.id')
                ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
                ->with('product_detail')
                ->with('view_product')
                ->where('cart.user_id',$user_id)
                ->where('cart.status' ,'active')
                ->where('products.status', 'active')
                ->where('vendors.id', $id)
                ->whereNotNull('product_details.id')
                ->where('in_checkout', true)
                ->count();
        
        $total = Cart::leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
            ->leftJoin('products', 'product_details.product_id', 'products.id')
            ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
            ->with('product_detail')
            ->with('view_product')
            ->where('cart.user_id',$user_id)
            ->where('cart.status' ,'active')
            ->where('products.status', 'active')
            ->where('vendors.id', $id)
            ->whereNotNull('product_details.id')
            ->count();

        if($checkout == $total){
            return true;
        }
        
        return false;
    }
}
