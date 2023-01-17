<?php

namespace App\Http\Controllers\Admin;

use App\Backlink;
use App\Http\Controllers\Controller;
use App\InviteRules;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InviteController extends Controller
{
    public function index() {
        return view('admin.invite.index');
    }

    public function invited_user_dt(Request $request){
        $user = User::where('referal_code', $request->code)->first();
        $data = User::where('parent_id', $user->id)->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function invite_friends() {
        return view('admin.invite.users');
    }

    public function user_dt() {
        $users = User::where('is_vendor' , false)->with('user_addresses')->whereNotNull('referal_code')->orderByDesc('id');
        return DataTables::of($users->get())->addIndexColumn()->make(true);
    }

    public function rules() {
        return view('admin.invite.rules.index');
    }

    public function rule_dt() {
        $data = InviteRules::orderByDesc('id');
        return DataTables::of($data->get())->addIndexColumn()->make(true);
    }

    public function rule_create() {
        return view('admin.invite.rules.create');
    }

    public function rule_submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'count_from' => 'required',
            'count_after' => 'required',
            'percentage' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            InviteRules::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'count_from' => $request->count_from,
                'count_after' => $request->count_after,
                'percentage' => $request->percentage,
                'status' => $request->status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::commit();
            return json_encode(['status' => true , 'message' => 'Invite rule created!']);
        } catch (\Throwable $e) {
            Log::channel('errorlog')->info(['module'=> 'InviteRule', 'message' => $e->getMessage(), 'query' => '']);
            DB::rollback();
            return json_encode(['status' => false , 'message' => $e->getMessage()]);
        }
    }

    public function rule_edit($id) {
        $rule = InviteRules::findOrFail($id);
        return view('admin.invite.rules.edit', compact('rule'));
    }

    public function rule_update(Request $request) {
        $rule = InviteRules::where('id', $request->id)->first();

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $rule->start_date = $request->start_date;
            $rule->end_date = $request->end_date;
            $rule->count_from = $request->count_from;
            $rule->count_after = $request->count_after;
            $rule->percentage = $request->percentage;
            $rule->status = $request->status;
            $rule->save();

            DB::commit();
            return json_encode(['status' => true , 'message' => 'Invite rule edited!']);
        } catch (\Throwable $e) {
            Log::channel('errorlog')->info(['module'=> 'InviteRule', 'message' => $e->getMessage(), 'query' => '']);
            return json_encode(['status' => false , 'message' => $e->getMessage()]);
        }
    }
}
