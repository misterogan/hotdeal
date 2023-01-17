<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\FAQ;
use App\FlashSale;
use App\FlashSaleDetail;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Privacy;
use App\Product;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class PrivacyPolicyController extends Controller
{
    public function index(Request $request){
        return view('admin.privacy.index');
    }

    public function create_view(Request $request) {
        $id = Auth::user()->id;
        return view('admin.privacy.create', compact('id'));
    }

    public function privacy_dt() {
        $privacy = Privacy::orderBy('id');

        return DataTables::of($privacy->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        $privacy = Privacy::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => str_replace(' ', '-', strtolower($request->title)),
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        if ($privacy) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $privacy = Privacy::where('id', $id)->first();

        return view('admin.privacy.edit', compact('id', 'privacy'));
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();
        $data = Privacy::where('id', $request->id)->first();

        $privacy_dt = Privacy::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $request->slug,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if ($privacy_dt > 0) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }
}


