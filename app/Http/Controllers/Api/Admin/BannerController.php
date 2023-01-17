<?php

namespace App\Http\Controllers\Api\admin;

use App\Banner;
use App\Http\Controllers\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Utils;

class BannerController extends Api
{
    static $FOLDER_PATH = "/upload/banner/";
    /**
     * @OA\Post(
     * path="/api/admin/banner/create",
     * summary="create",
     * description="banner create",
     * operationId="bannercreate",
     * tags={"Admin"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="img_url",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="video_url",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="file"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="url",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="type",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="placement",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="sequence",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
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
    public function create(Request $request){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'img_url' => 'required',
            'video_url' => 'required',
            'url' => 'required',
            'type' => 'required',
            'placement' => 'required',
            'sequence' => 'required',
            'status' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        $file = $request->img_url;
        $upload_image = Utils::upload_galery($file,static::$FOLDER_PATH);

        if(Request::hasFile('video_url')){
            $file = Request::file('video_url');
            $upload_video = Utils::upload_galery($file,static::$FOLDER_PATH);
        }

        if($upload_image){
            Banner::create([
                'name'          =>  $request->name,
                'img_url'       =>  $upload_image,
                'video_url'     =>  $upload_video,
                'url'           =>  $request->url,
                'type'          =>  'image', //image;video
                'placement'     =>  $request->placement,
                'sequence'      =>  $request->sequence,
                'status'        =>  ($request->status === 1)? 'active':'inactive',
            ]);
        }



    }
}
