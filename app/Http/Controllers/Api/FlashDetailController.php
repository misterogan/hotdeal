<?php

namespace App\Http\Controllers\Api;

use App\FlashSale;
use App\HighlightProduct;
use App\Http\Resources\FlashSaleResource;
use App\Http\Resources\HighlightProductsResource;
use App\Http\Resources\ProductSuggestionResource;
use App\Http\Resources\StrengthsResource;
use App\ProductSuggestion;
use App\Strength;
use Illuminate\Http\Request;

class FlashDetailController extends Api
{
    /**
     * @OA\Post(
     * path="/api/flash-sale/detail/get",
     * summary="flashsale detail",
     * description="get flash sale detail",
     * operationId="get flash sale detail",
     * tags={"Flash-Sale"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="slug",
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

    public function flash_sale_detail(Request $request){

        $user = $this->user();
        $flash_sale  = FlashSale::with('flash_sale_detail','flash_sale_detail.products','flash_sale_detail.products.review')
            ->where('slug',$request->slug)->get();

        $product_suggestion = ProductSuggestion::where('status','active')->get();
        $strengths = Strength::where('status','active')->get();
        $vid_highlight_product  = HighlightProduct::with('products')->where('status','active')->where('highlight_type','2')->get();

        $data = [
            'flash_sale'=>FlashSaleResource::collection($flash_sale),
        ];
        return $this->successResponse($data);
    }

}
