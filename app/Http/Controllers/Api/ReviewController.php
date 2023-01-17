<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewsResource;
use App\OrderDetail;
use App\Product;
use App\ProductGallery;
use App\Review;
use App\ReviewGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Api
{
    /**
     * @OA\Post(
     * path="/api/review/get",
     * summary="Review",
     * description="post Review",
     * operationId="post Review",
     * tags={"Review"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="product_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="all_review",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="with_video",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="rating",
     *          required=false,
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
    public function get(Request $request) {
        $reviews = Review::where('product_id', $request->product_id)
                    ->with('review_gallery')
                    ->with('user')
                    ->with('image')
                    ->with('vendor_review');
        
        if($request->filter != '') {
            if($request->filter == 'all') {
                $reviews = $reviews->orderBy('is_with_video', 'DESC')->orderBy('created_at' , 'DESC')->paginate(10);
            }
            if($request->filter == 'with_video') {
                $reviews = $reviews->where('is_with_video', true)->paginate(10);
            }
            if($request->filter == 'rating_5') {
                $reviews = $reviews->where('rating', '>=', 5)->paginate(10);
            }
            if($request->filter == 'rating_4') {
                $reviews = $reviews->where('rating', 4)->paginate(10);
            }
            if($request->filter == 'rating_3') {
                $reviews = $reviews->where('rating', 3)->paginate(10);
            }
            if($request->filter == 'rating_2') {
                $reviews = $reviews->where('rating', 2)->paginate(10);
            }
            if($request->filter == 'rating_1') {
                $reviews = $reviews->where('rating', 1)->paginate(10);
            }
            if($request->filter == 'newest') {
                $reviews = $reviews->orderBy('created_at', 'DESC')->paginate(10);
            }
            if($request->filter == 'high_rating') {
                $reviews = $reviews->orderBy('rating', 'DESC')->paginate(10);
            }
            if($request->filter == 'low_rating') {
                $reviews = $reviews->orderBy('rating')->paginate(10);
            }
        } else {
            $reviews = $reviews->orderBy('created_at' , 'DESC')->paginate(10);
        }


        $ven = Product::with('vendor')->where('id', $request->product_id)->first();

        $vendor['name'] = $ven->vendor->name;
        $vendor['image'] = $ven->vendor->image;

        $response['reviews'] = $reviews;
        $response['vendor'] = $vendor;

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/review/submit",
     * summary="Review",
     * description="submit Review",
     * operationId="submit Review",
     * tags={"Review"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="product_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="user_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="rating",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="review",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="is_with_video",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="photo_1",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="photo_2",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="video",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="file"
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
    public function submit(Request $request) {
        $user = $this->user();
        if(!$user){
            return $this->errorResponse(self::ERROR_NOT_LOGIN , self::ERROR_NOT_LOGIN_CODE);
        }
        $validate_invoice = OrderDetail::select('orders.user_id')->leftJoin('orders' , 'orders.id', 'order_details.order_id')->where('order_details.invoice_number' , $request->forms['inv'])->where('orders.user_id' , $user->id)->first();

        if(!$validate_invoice){
             echo 'not yours';
             return;
        }
        if(count($request->forms['data']) > 0){
            try {
                DB::beginTransaction();
                foreach($request->forms['data']  as $key => $item){
                    $review = Review::create(
                        [
                            'user_id' => $user->id,
                            'order_details_id' => $item['order_details_id'],
                            'product_id' => $item['product'],
                            'rating' => $item['ratings'],
                            'review' => $item['reviews'] == null ? '' : $item['reviews'],
                            'status' => 'active',
                            'is_with_video' => $item['video'] != '' || $item['picture'] != '' ? true : false,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_by' => $user->name,
                            'updated_by' => $user->name,
                        ]
                    );
                    if($item['picture'] != ''){
                        $picture = $item['picture'];
                        $type = '';
                        if($picture != ''){
                            if(in_array($picture->getClientOriginalExtension() , static::IMAGE_MIME)){
                                $folder = '/upload/review/image/';
                                $file_name = uniqid();
                                $filename = $file_name .  '.' . $picture->getClientOriginalExtension();
                                $upload = Utils::upload_without_watermark($folder,$filename,$picture);
                                ReviewGallery::create([
                                    'review_id' => $review->id,
                                    'type' => 'image',
                                    'url_source' => $upload,
                                    'status' => 'active',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }else{
                                return $this->errorResponse('Format Tidak Sesuai' ,300);
                            }
                        }
                    } else{
                        ReviewGallery::create([
                            'review_id' => $review->id,
                            'type' => 'image',
                            'status' => 'active',
                            'url_source' => '',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                    if($item['video'] != ''){
                        $video = $item['video'];
                        $type = '';
                        if($video != ''){
                            if(in_array($video->getClientOriginalExtension() , static::VIDEO_MIME)){
                                $folder = '/upload/review/video/';
                                $file_name = uniqid();
                                $filename = $file_name .  '.' . $video->getClientOriginalExtension();
                                $upload = Utils::upload_video($folder,$video,$filename);
                                ReviewGallery::create([
                                    'review_id' => $review->id,
                                    'type' => 'video',
                                    'status' => 'active',
                                    'url_source' => $upload,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }else{
                                return $this->errorResponse('Format Tidak Sesuai' ,300);
                            }
                        }
                    } else{
                        ReviewGallery::create([
                            'review_id' => $review->id,
                            'type' => 'video',
                            'status' => 'active',
                            'url_source' => '',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return  $this->errorResponse($e->getMessage(), static::ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH_CODE);
                //throw $th;
            }
        }

        return $this->successResponse([]);
    }

    /**
     * @OA\Delete(
     * path="/api/review/delete/{id}",
     * summary="Review",
     * description="delete Review",
     * operationId="delete Review",
     * tags={"Review"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="id",
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
    public function delete(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $review = Review::where('id', $request->id)->update([
            'status' => 'inactive',
            'updated_at' => Carbon::now(),
            'updated_by' => $this->user()->name,
        ]);

        if ($review >= 1) {
            return $this->successResponse($review);
        } else {
            return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA , self::ERROR_WHEN_SAVE_DATA_CODE);
        }
    }

    public function user_review(Request $request){
        $user = $this->user();

        $review = Review::where('order_details_id', $request->id)->where('user_id', $user->id)->where('status', 'active')->first();
        $data['review'] = $review;
        return $this->successResponse($data);
    }

    public function update(Request $request) {
        // print_r($request->all());
        $user = $this->user();
        if(!$user){
            return $this->errorResponse(self::ERROR_NOT_LOGIN , self::ERROR_NOT_LOGIN_CODE);
        }
        $validate_invoice = OrderDetail::select('orders.user_id')->leftJoin('orders' , 'orders.id', 'order_details.order_id')->where('order_details.invoice_number' , $request->forms['inv'])->where('orders.user_id' , $user->id)->first();
        $order_detail = OrderDetail::where('invoice_number', $request->forms['inv'])->first();

        if(!$validate_invoice){
             echo 'not yours';
             return;
        }
        // dd($request->forms['review_id']); 

        foreach($request->forms['data']  as $key => $item){
        }
        if(count($request->forms['data']) > 0){

            try {
                DB::beginTransaction();
                foreach($request->forms['data']  as $key => $item){
                    // dd($item);
                    $review = Review::where('id', $item['review_id'])->update(
                        [
                            'user_id' => $user->id,
                            'order_details_id' => $item['order_details_id'],
                            'product_id' => $item['product'],
                            'rating' => $item['ratings'] ? $item['ratings'] : 5,
                            'review' => $item['reviews'] == null ? '' : $item['reviews'],
                            'is_with_video' => $item['video'] != '' || $item['picture'] != '' ? true : false,
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_by' => $user->name,
                            'updated_by' => $user->name,
                        ]
                    );

                    if($item['picture'] != ''){
                        $picture = $item['picture'];
                        $type = '';
                        if($picture != ''){
                            ReviewGallery::where('review_id', $item['review_id'])->where('type', 'image')->where('status', 'active')->update([
                                'status' => 'inactive'
                            ]);
                            if(in_array($picture->getClientOriginalExtension() , static::IMAGE_MIME)){
                                $folder = '/upload/review/image/';
                                $file_name = uniqid();
                                $filename = $file_name .  '.webp';
                                $upload = Utils::upload_without_watermark($folder,$filename,$picture);
                                ReviewGallery::create([
                                    'review_id' => (int)$item['review_id'],
                                    'type' => 'image',
                                    'url_source' => strtolower($upload),
                                    'status' => 'active',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }else{
                                return $this->errorResponse('Format Tidak Sesuai' ,300);
                            }
                        }
                    }
                    // dd($item['video']);
                    if($item['video'] != ''){
                        $video = $item['video'];
                        $type = '';
                        if($video != ''){
                            ReviewGallery::where('review_id', $item['review_id'])->where('type', 'video')->where('status', 'active')->update([
                                'status' => 'inactive'
                            ]);
                            if(in_array($video->getClientOriginalExtension() , static::VIDEO_MIME)){
                                $folder = '/upload/review/video/';
                                $file_name = uniqid();
                                $filename = $file_name .  '.' . $video->getClientOriginalExtension();
                                $upload = Utils::upload_video($folder,$video,$filename);
                                $create = ReviewGallery::create([
                                    'review_id' => (int)$item['review_id'],
                                    'type' => 'video',
                                    'url_source' => strtolower($upload),
                                    'status' => 'active',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }else{
                                return $this->errorResponse('Format Tidak Sesuai' ,300);
                            }
                        }
                    }
                }
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return  $this->errorResponse($e->getMessage(), static::ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH_CODE);
                //throw $th;
            }
        }

        return $this->successResponse([]);
        if ($review->exists) {
            return $this->successResponse($review);
        } else {
            return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA , self::ERROR_WHEN_SAVE_DATA_CODE);
        }
    }

    public function carousel(Request $request){
        $medias = ReviewGallery::select('url_source', 'type')->where('review_id', $request->id)->where('status', 'active')->where('url_source', '!=', '')->get();
        return $this->successResponse($medias, 200);
    }

}
