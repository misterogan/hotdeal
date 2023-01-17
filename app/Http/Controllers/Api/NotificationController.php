<?php

namespace App\Http\Controllers\Api;

use App\Notification;
use App\NotificationDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Api
{
    /**
     * @OA\Get(
     * path="/api/notification/get",
     * summary="get",
     * description="notification get",
     * operationId="notificationget",
     * tags={"User"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function get() {
        
        $user = $this->user();
        if(!$user){
            return $this->successResponse([]);
        }
        $notifications_home = $notifications = NotificationDetail::where('user_id', $user->id)->with('notification')->orderByDesc('created_at')->limit(3)->get();
        $data = [
            'notifications' => $notifications,
            'notifications_home' => $notifications_home,
        ];

        return $this->successResponse($data);
    }

    public function all_notification(Request $request) {
        $user = $this->user();
        if(!$user){
            return $this->successResponse([]);
        }
        $notifications = NotificationDetail::where('user_id', $user->id)->with('notification');
        
        //echo Carbon::now()->subWeek();
        if(isset($request->filter)){
            if($request->filter == 'unread'){
                $notifications->where('is_read' , false);
            }
            if($request->filter == 'day'){
                $notifications->where('created_at', '>=', Carbon::yesterday());
            }
            if($request->filter == 'month'){
                $notifications->where('created_at', '>=', Carbon::now()->subMonth());
            }
            if($request->filter == 'week'){
                $notifications->where('created_at', '>=', Carbon::now()->subWeek());
            }
            if($request->filter == 'read'){
                $notifications->where('is_read' , true);
            }
            
        }
        $data = $notifications->orderByDesc('created_at')->paginate(10);
        
        return $this->successResponse($data);
    }

    /**
     * @OA\Post(
     * path="/api/notification/mark/read",
     * summary="get",
     * description="notification Mark Read",
     * operationId="notificationread",
     * tags={"User"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="id",
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
    public function mark_as_read(Request $request) {
        if($request->id == 'all'){
            NotificationDetail::where('user_id' , Auth::user()->id) ->update([
                'is_read' => true,
            ]);
            return $this->successResponse([]);
        }
        $notification = NotificationDetail::where('id' , $request->id)->where('user_id' , Auth::user()->id)->first();
        if($notification){
            $notification->is_read = true;
            if($notification->save()){
            }
        }
        return $this->successResponse([]);
    }
}
