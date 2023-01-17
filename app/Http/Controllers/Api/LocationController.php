<?php

namespace App\Http\Controllers\Api;

use App\Area;
use App\Cities;
use App\Http\Controllers\Controller;
use App\Province;
use App\Suburbs;
use Illuminate\Http\Request;

class LocationController extends Api
{

    /**
     * @OA\Get(
     * path="/api/location/province/get",
     * summary=" Location",
     * description="get locationl",
     * operationId="get locationl",
     * tags={"Location"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function province(){
        $province = Province::select('id','name','name as text','lat','lng')->get();
        return $this->successResponse($province);
    }


    /**
 * @OA\Post(
 * path="/api/location/cities/get",
 * summary=" Location",
 * description="get city location",
 * operationId="get city location",
 * tags={"Location"},
 * security={ {"bearer": {} }},
 *     @OA\Parameter(
 *          name="province",
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

    public function city(Request $request){

        $province = $request->province;
        $cities = Cities::select('id','name','name as text','lat','lng')->where('province_id',$province)->get();
        return $this->successResponse($cities);

    }

    /**
     * @OA\Post(
     * path="/api/location/suburbs/get",
     * summary=" Location",
     * description="get suburbs location",
     * operationId="get suburbs location",
     * tags={"Location"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="city",
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

    public function suburbs(Request $request){

        $city = $request->city;
        $suburbs = Suburbs::select('id','name','name as text','lat','lng')->where('city_id',$city)->get();
        return $this->successResponse($suburbs);

    }

    /**
     * @OA\Post(
     * path="/api/location/area/get",
     * summary=" Location",
     * description="get area location",
     * operationId="get area location",
     * tags={"Location"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="suburb_id",
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

    public function area(Request $request){
        $suburb = $request->suburb_id;
        $area = Area::select('id','name','name as text','lat','lng')->where('suburb_id',$suburb)->get();
        return $this->successResponse($area);
    }
}
