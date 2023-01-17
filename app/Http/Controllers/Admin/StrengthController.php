<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\FAQ;
use App\FlashSale;
use App\FlashSaleDetail;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Product;
use App\Strength;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class StrengthController extends Controller
{
    public function index(Request $request){
        return view('admin.strengths.index');
    }

    public function create_view(Request $request) {
        $id = Auth::user()->id;
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();

        return view('admin.strengths.create', compact('id', 'products', 'categories'));
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $strength = Strength::where('id', $id)->first();

        return view('admin.strengths.edit', compact('id', 'strength'));
    }

    public function strength_dt() {
        $strengths = Strength::orderBy('id');

        return DataTables::of($strengths->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-faq' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/faq/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
        } else {
            $upload = null;
        }

        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'image' =>  $upload,
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        if ($faq) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();
        $data = Strength::where('id', $request->id)->first();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-faq' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/strengths/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
        } else {
            $upload = $data->image;
        }

        $faq = Strength::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' =>  $upload,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if ($faq > 0) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }
}
