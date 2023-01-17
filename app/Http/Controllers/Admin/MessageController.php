<?php

namespace App\Http\Controllers\Admin;

use App\Message;
use App\Http\Controllers\Controller;
use App\Notification;
use App\NotificationDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    public function index(Request $request) {
        $messages = Message::orderByDesc('created_at')->get();

        return view('admin.message.index', compact('messages'));
    }

    public function create_view() {
        $users = User::where('status', 'active')->orderBy('id')->get();

        return view('admin.message.create', compact('users'));
    }

    public function message_dt(Request $request) {
        $query = Message::with('recipient')->orderByDesc('created_at');
        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }

    public function create(Request $request) {
        $user = Auth::user();

        $validation = Validator::make($request->all(), [
            'recipient' => 'required',
            'message' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $new_message = Message::create([
            'sender' => $user->id,
            'recipient' => $request->recipient,
            'title' => $request->title,
            'message' => $request->message,
            'url' => isset($request->url) ? $request->url : '/notification',
            'is_read' => false,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name
        ]);

        $new_notif = Notification::create([
            'title' => $request->title,
            'body' => $request->message,
            'url' => isset($request->url) ? $request->url : '/notification',
            'image' => '',
            'status' => 'active',
            'send_to' => 'uid',
            'topic' => $request->title,
            'send_by' => 'admin',
            'browser' => 'website',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
        ]);

        if ($new_notif->first()) {
            $new_notif_detail = NotificationDetail::create([
                'notification_id' => $new_notif->id,
                'user_id' => $request->recipient,
                'is_read' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($new_notif_detail->first()) {
                return json_encode(['status'=> true, 'message'=> "Success"]);
            } else {
                return json_encode(['status'=> false, 'message'=> ["Something went wrong."]]);
            }
        } else {
            return json_encode(['status'=> false, 'message'=> ["Something went wrong."]]);
        }
    }
}
