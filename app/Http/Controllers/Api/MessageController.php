<?php

namespace App\Http\Controllers\Api;

use App\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageController extends Api
{
    /**
     * @OA\Get(
     * path="/api/message/get",
     * summary="get",
     * description="message get",
     * operationId="messageget",
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

        $message_home = Message::where('user_id', $user->id)->where('status', 'active')->orderByDesc('created_at')->limit(2)->get();
        $messages = Message::where('user_id', $user->id)->where('status', 'active')->orderByDesc('created_at')->get();
        $messages_yesterday = Message::where('user_id', $user->id)->where('status', 'active')->where('created_at', '>=', Carbon::yesterday())->orderByDesc('created_at')->get();
        $messages_last_week = Message::where('user_id', $user->id)->where('status', 'active')->where('created_at', '>=', Carbon::now()->subWeek())->orderByDesc('created_at')->get();
        $messages_last_month = Message::where('user_id', $user->id)->where('status', 'active')->where('created_at', '>=', Carbon::now()->subMonth())->orderByDesc('created_at')->get();
        $read_messages = Message::where('user_id', $user->id)->where('status', 'active')->where('is_read', true)->orderByDesc('created_at')->get();
        $unread_messages = Message::where('user_id', $user->id)->where('status', 'active')->where('is_read', false)->orderByDesc('created_at')->get();

        $data = [
            'messages' => $messages,
            'messages_home' => $message_home,
            'messages_yesterday' => $messages_yesterday,
            'messages_last_week' => $messages_last_week,
            'messages_last_month' => $messages_last_month,
            'read_messages' => $read_messages,
            'unread_messages' => $unread_messages,
            'unread_count' => $unread_messages->count()
        ];

        return $this->successResponse($data);
    }
}
