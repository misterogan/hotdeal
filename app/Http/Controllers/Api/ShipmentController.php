<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Logistics;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Shipment;
use App\ShipmentTrackerLog;
use App\OrderDetailShippingLogs;
use Illuminate\Http\Request;

class ShipmentController extends Api
{

    /**
     * @OA\Get(
     * path="/api/shipment/get",
     * summary="Shipment",
     * description="get shipment",
     * operationId="get shipment",
     * tags={"Shipment"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function get(Request $request){
        $shipment = Shipment::with('services')->where('status','active')->get();
        $data = ShipmentResource::collection($shipment);
        return $this->successResponse($data);
    }

    /**
     * @OA\Post(
     * path="/api/shipment/check/price",
     * summary="Shipment",
     * description="check pricing",
     * operationId="check pricing",
     * tags={"Shipment"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="origin_area_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="origin_lat",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="origin_lng",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="origin_suburb_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="destination_area_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="destination_lat",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="destination_lng",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="destination_suburb_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="for_order",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="cod",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="boolean"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="height",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="length",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="width",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="weight",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="item_value",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="limit",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="page",
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

    public function get_pricing(Request $request){
        $url    = 'https://merchant-api-sandbox.shipper.id/v3/pricing/domestic';
        $data   = array(
            "origin"                => array("area_id"=>$request->origin_area_id,"lat"=>$request->origin_lat,"lng"=>$request->origin_lng,"suburb_id"=>$request->origin_suburb_id),
            "destination"           =>array("area_id"=>$request->destination_area_id,"lat"=>$request->destination_lat,"lng"=>$request->destination_lng,"suburb_id"=>$request->destination_suburb_id),
            "for_order"             =>$request->for_order,
            "cod"                   =>$request->cod,
            "height"                =>$request->height,
            "length"                =>$request->length,
            "width"                 =>$request->width,
            "weight"                =>$request->weight,
            "item_value"            =>$request->item_value,
            "limit"                 =>$request->limit,
            "page"                  =>$request->page,
            "sort_by"               =>array('final_price')
        );
        $postdata = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "x-api-key: aDHFyFwtKjcJmSxiaGsyGKXwvlEN0gLvse2moy8ZgTMHce8JGfMunHgsOSqlFFsL",
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $json= json_decode($response, true);
        return $this->successResponse($json);
    }


    public function create_pickup (Request $request){
        $order_id = $request->order_id;
        Logistics::createPickupLogistic('SHIPPER' , $order_id );
    }

    public function get_order(Request $request){
        $order_id = $request->order_id;
        print_r(Logistics::get_order('SHIPPER' , $order_id));
    }

    public function webhook_logistic(Request $request){

        // OrderDetailShippingLogs::create(
        //     $data
        // );
        // $data = [
        //     'order_detail_id' => 1,
        //     'endpoint' => 'webhook',
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'payload' => 'no payload',
        //     'response' => json_encode($request->all())
        // ];
        // OrderDetailShippingLogs::create(
        //     $data
        // );
        
        Logistics::webhookLogistic('SHIPPER' ,  $request);
        return $this->successResponse([]);
        
    }

}
