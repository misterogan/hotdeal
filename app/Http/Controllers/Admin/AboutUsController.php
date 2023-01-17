<?php

namespace App\Http\Controllers\Admin;

use App\AboutUs;
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

class AboutUsController extends Controller
{
    public function index(Request $request){
        $about = AboutUs::where('id', 1)->first();

        return view('admin.about.index', compact('about')); 
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }
        // dd(request('message'));
        $user = Auth::user();
        $about = AboutUs::where('id', 1)->first();

        $about->message = $request->message;
        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-about' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/about/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);

            if ($upload) {
                $about->image = $upload;
            }
        }
        $about->updated_at = Carbon::now();
        $about->updated_by = $user->name;

        if ($about->save()) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }
}
