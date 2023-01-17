<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\BannerEvent;
use App\FlashSale;
use App\HighlightProduct;
use App\Http\Resources\BannerEventResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\FlashSaleResource;
use App\Http\Resources\HighlightProductsResource;
use App\Http\Resources\ProductSuggestionResource;
use App\Http\Resources\StrengthsResource;
use App\ProductSuggestion;
use App\Strength;
use Illuminate\Http\Request;


class HomeController extends Api
{

    /**
     * @OA\Get(
     * path="/api/home/strength/get",
     * summary="strengh",
     * description="get strengh",
     * operationId="get strengh",
     * tags={"HomePage"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function strength_get(Request $request)
    {
        $strengths = Strength::where('status','active')->get();
        $data = [
            'strengths'=>StrengthsResource::collection($strengths),
        ];
        return $this->successResponse($data);
    }





}
