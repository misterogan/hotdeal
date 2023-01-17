<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\BannerAdditional;
use App\BannerEvent;
use App\Http\Resources\BannerEventResource;
use App\Http\Resources\BannerResource;
use App\RejekiNomplokBanner;
use Illuminate\Http\Request;

class BannerController extends Api
{
    /**
     * @OA\Post(
     * path="/api/banner/get",
     * summary="Banner",
     * description="get Banner",
     * operationId="get Banner",
     * tags={"Banner"},
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
    public function get_banner(Request $request)
    {
        $banners = Banner::where('status','active')->orderBy('sequence' , 'ASC')->get();
        $data = [
            'banner' =>  BannerResource::collection($banners),
            'rejeki_banner' => RejekiNomplokBanner::pluck('banner')->first()
        ];
        return $this->successResponse($data);
    }

    /**
     * @OA\Post(
     * path="/api/banner/event/get",
     * summary="banner-event",
     * description="get banner-event",
     * operationId="get banner-event",
     * tags={"Banner"},
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
    public function banner_event_get(Request $request)
    {
        $banners_event = BannerEvent::where('status','active')->get();

        $data = [
            'banner_event'=>BannerEventResource::collection($banners_event),
        ];
        return $this->successResponse($data);
    }

    public function login_popup_banner(){
        $banner = BannerAdditional::where('position', 'login')->where('status', 'active')->first();
        return $this->successResponse($banner);
    }

}
