<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Category;
use App\FAQ;
use App\FlashSale;
use App\FlashSaleDetail;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Product;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class AdministratorController extends Controller
{
    public function index(Request $request){
        return view('admin.administrator.index');
    }

    public function create_view(Request $request) {
        $id = Auth::user()->id;
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();

        return view('admin.administrator.create', compact('id', 'products', 'categories'));
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $admin = Admin::where('id', $id)->first();

        return view('admin.administrator.edit', compact('id', 'admin'));
    }

    public function administrator_dt() {
        $administrator = Admin::orderBy('id', 'desc');

        return DataTables::of($administrator->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        if ($admin) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();
        $data = Admin::where('id', $request->id)->first();

        $data->name = $request->name;
        $data->email = $request->email;
        if ($request->filled('password')) {
            $password = Hash::make($request->password);
            $data->password = $password;
        }
        $data->updated_at = Carbon::now();
        $data->updated_by = $user->name;
        $data->save();

        if ($data->save()) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }
}
