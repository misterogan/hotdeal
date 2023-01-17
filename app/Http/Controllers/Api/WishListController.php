<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Api;
use App\Http\Resources\CustomerProductsResource;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\WishlistResource;
use App\Product;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishListController extends Api
{
    /**
     * @OA\Post(
     * path="/api/wishlist/create",
     * summary="WishList Delete and create in one API",
     * description="Wish List",
     * operationId="create",
     * tags={"WishList"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="product",
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
    public function create(Request $request) {
    
        $validation = Validator::make($request->all(), [
            'slug' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), self::ERROR_VALIDATION_CODE);
        }
        $user = $user = $this->user();
        $get_product = Product::where('slug' , $request->slug)->first();
        if(!$get_product){
            return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE , 401);
        }
        $data = [
            'user_id' => $user->id,
            'product_id' => $get_product->id,
            'created_at' => $this->now(),
            'updated_at' => $this->now()
        ];
        $exists = Wishlist::where('product_id' , $get_product->id)->where('user_id' , $user->id)->first();
        if($exists){
            $exists->updated_at = $this->now();
            $exists->status = $exists->status == 'active' ? 'deleted' : 'active';
            if($exists->save()){
                return $this->successResponse($exists->status , self::SUCCESS_MESSAGE_UPDATE);
            }else{
                return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE , 401);
            };
        }else{
            if(Wishlist::insert($data)){
                return $this->successResponse('active' , self::SUCCESS_MESSAGE_UPDATE);
            }else{
                return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE , 401);
            }
        }
        
    }


    /**
     * @OA\Get(
     * path="/api/wishlist/list",
     * summary="List of Wishlist",
     * description="WishList",
     * operationId="list",
     * tags={"WishList"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function list() {
        $user = $this->user();
        $flash_sale  = Product::select('products.*')->join('wishlist' ,'products.id','wishlist.product_id')
        ->with('review')
        ->with('detail')
        ->with('image')
        ->with('galleries')
        ->with('in_wish_list')
        ->where('products.status', 'active')
        ->where('wishlist.status','active')
        ->where('wishlist.user_id' , $user->id)
        ->orderby('wishlist.updated_at', 'DESC')->get();
        $data = [];
        if($flash_sale){
            $data = CustomerProductsResource::collection($flash_sale);
        }
        return $this->successResponse($data);
    }


    public function delete_byarray(Request $request) {
    
        $validation = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), self::ERROR_VALIDATION_CODE);
        }
        $user = $this->user();
        if($request->status == 'all'){
            Wishlist::where('user_id' , $user->id)->where('status' , 'active')->update([
                'status' => 'inactive'
            ]);
        }else{
            Wishlist::whereIn('product_id' , $request->item)->where('user_id' , $user->id)->update([
                'status' => 'inactive'
            ]);
        }
        
        return $this->successResponse([] , self::SUCCESS_MESSAGE_UPDATE);

        // $get_product = Product::where('slug' , $request->slug)->first();
        // if(!$get_product){
        //     return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE , 401);
        // }
        // $data = [
        //     'user_id' => $user->id,
        //     'product_id' => $get_product->id,
        //     'created_at' => $this->now(),
        // ];
        // $exists = Wishlist::where('product_id' , $get_product->id)->where('user_id' , $user->id)->first();
        // if($exists){
        //     $exists->updated_at = $this->now();
        //     $exists->status = $exists->status == 'active' ? 'deleted' : 'active';
        //     if($exists->save()){
        //         return $this->successResponse([] , self::SUCCESS_MESSAGE_UPDATE);
        //     }else{
        //         return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE , 401);
        //     };
        // }else{
        //     if(Wishlist::insert($data)){
        //         return $this->successResponse([] , self::SUCCESS_MESSAGE_UPDATE);
        //     }else{
        //         return $this->errorResponse(self::ERROR_MESSAGE_WHEN_SAVE , 401);
        //     }
        // }
        
    }

}
