<?php

namespace App\Http\Controllers\Admin;

use App\Area;
use App\Banner;
use App\Category;
use App\Cities;
use App\Countries;
use App\FlashSale;
use App\FlashSaleDetail;
use App\Helpers\Emails;
use App\Helpers\Utils;
use App\HighlightProduct;
use App\Http\Controllers\Controller;
use App\Product;
use App\Province;
use App\Suburbs;
use App\User;
use App\Vendor;
use App\VendorBanner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class VendorController extends Controller
{
    public function index(Request $request){
        return view('admin.vendor.index');
    }

    public function create_view(Request $request) {
        $users = User::orderBy('id')->get();
        $countries = Countries::get();
        $provinces = Province::get();
        $cities = Cities::get();
        return view('admin.vendor.create', compact('users', 'countries', 'provinces', 'cities'));
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $vendor = Vendor::where('id', $id)->first();
        $user = User::where('id', $vendor->user_id)->first();
        $countries = Countries::get();
        $provinces = Province::get();
        $cities = Cities::where('api_id', $vendor->city_id)->get();
        $suburbs = Suburbs::where('api_id', $vendor->suburb_id)->get();
        $areas = Area::where('api_id', $vendor->area_id)->get();

        return view('admin.vendor.edit', compact('id', 'vendor', 'user', 'countries', 'provinces', 'cities', 'suburbs', 'areas'));
    }

    public function vendor_dt() {
        $vendor = Vendor::with('user')->with('country')->with('province')->with('suburb')->orderBy('name');

        return DataTables::of($vendor->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'pic' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        if (User::where('email', $request->email)->first() !== null) {
            return json_encode(['status'=> false, 'message'=> ['Email is taken.']]);
        }

        $area = Area::where('api_id', $request->area_select)->first();
        $lat = !$request->lat ? $area->lat : $request->lat;
        $lng = !$request->long ? $area->lng : $request->long;

        $user = Auth::user();
        $password = $this->generate_password();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name .'-vendors' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/vendors/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload) {
                return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
            }
        }

        $user_create = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' =>  Hash::make($password),
            'is_vendor' => true,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);
        $province = Province::where('id',$request->province_select)->first();
        $city     = Cities::where('id',$request->city_select)->first();
        $surb     = Suburbs::where('id',$request->suburb_select)->first();
        $area     = Area::where('id',$request->area_select)->first();

        $vendor = Vendor::create([
            'name' => $request->name,
            'user_id' => $user_create->id,
            'image' => isset($upload) ? $upload : null,
            'description' => $request->has('description') ? $request->description : null,
            'pic' => $request->has('pic') ? $request->pic : null,
            'country_id' => 102,
            'province_id' => $request->has('province_select') ? $province->api_id : null,
            'city_id' => $request->has('city_select') ? $city->api_id : null,
            'suburb_id' => $request->has('suburb_select') ? $surb->api_id : null,
            'area_id' => $request->has('area_select') ? $area->api_id : null,
            'lat' => $lat,
            'lng' => $lng,
            'address' => $request->has('address') ? $request->address : null,
            'status' => 'active',
            'rating' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        Emails::send_email($request->username, $request->email, 'Vendor Password', $password, Emails::$PASSWORD);

        return json_encode(['status'=> true, 'message'=> 'Success']);
    }

    public function edit(Request $request) {
        $user = Auth::user();
        $vendor = Vendor::where('id', $request->vendor_id)->first();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name .'-vendors' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/vendors/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload) {
                return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
            }
        } else {
            $upload = $vendor->image;
        }
        // update phone vendor
        if($request->phone) User::where('id' , $vendor->user_id)->update(['phone' => $request->phone]);
        $vendor = Vendor::where('id', $request->vendor_id)->update([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'pic' => $request->pic,
            'image' => $upload,
            'description' => $request->has('description') ? $request->description : null,
            'province_id' => $request->has('province_select') ? $request->province_select : null,
            'city_id' => $request->has('city_select') ? Cities::where('id' , $request->city_select)->pluck('api_id')->first() : null,
            'suburb_id' => $request->has('suburb_select') ? Suburbs::where('id' ,  $request->suburb_select)->pluck('api_id')->first() : null,
            'area_id' => $request->has('area_select') ? Area::where('id', $request->area_select)->pluck('api_id')->first() : null,
            'address' => $request->has('address') ? $request->address : null,
            'lng' => $request->has('long') ? $request->long : null,
            'lat' => $request->has('lat') ? $request->lat : null,
            'active_sameday' => $request->sameday,
            'status' => 'active',
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);


        if ($vendor > 0) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> ['Failed']]);
        }
    }

    public function generate_password() {
        $characters = "ABCDEFGHJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789@#$%&*+";
        srand((double)microtime()*1000000);
        $i = 0;
        $password = '' ;
        while ($i <= 12) {
            $num = rand() % 33;
            $tmp = substr($characters, $num, 1);
            $password = $password . $tmp;
            $i++;
        }
        return $password;
    }

    public function merchant($id) {
        $vendor = Vendor::findOrFail($id);
        $products = Product::where('vendor_id', $vendor->id)->where('status', 'active')->get();
        $selected_products = HighlightProduct::where('highlight_type', 6)->pluck('product_id')->toArray();
    
        return view('admin.merchant.detail', compact('vendor', 'products', 'selected_products'));
    }

    public function highlight(Request $request) {
        $user = Auth::user();

        $highlight_product = HighlightProduct::where('highlight_type', 6)->pluck('product_id')->toArray();

        if(count($request->product_selection) > 12) {
            return json_encode(['status'=> false, 'message'=> ['Batas highlight product 12!']]);
        }

        if ($request->product_selection) {
            $different_ids = array_diff($highlight_product, $request->product_selection);
            foreach ($different_ids as $product) {
                $query = HighlightProduct::where('product_id', $product)->first();
                if ($query) {
                    HighlightProduct::where('product_id', $product)->delete();
                }
            }
            foreach ($request->product_selection  as $product) {
                $highlight = HighlightProduct::create([
                    'product_id' => $product,
                    'highlight_type' => 6,
                    'sequence' =>  '1',
                    'status' => 'active',
                    'deep_link' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => $user->name,
                    'updated_by' => $user->name,
                ]);
            }
        }

        return json_encode(['status'=> true, 'message'=> 'Success']);
    }

    public function banner_merchant(Request $request) {
        $banner = VendorBanner::where('vendor_id', $request->vendor_id)->where('status', 'active')->orderByDesc('id');
        return DataTables::of($banner->get())->addIndexColumn()->make(true);
    }

    public function submit_banner(Request $request) {
        $user = Auth::user();
        $vendor_id = $request->vendor_id;

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-banners' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        }
        $newtab = isset($request->newtab) ? true : false;
        if(isset($request->url)) {
            $newtab = true;
        }

        $banner = VendorBanner::create([
            'url' => isset($request->url) ? $request->url : null,
            'img_url' => $img,
            'vendor_id' => $vendor_id,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
            'new_tab' => $newtab,
        ]);

        if($banner) {
            return json_encode(['status' => true, 'message' => 'Success Upload Banner.']);
        }
        return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
    }

    public function banner_inactive(Request $request) {
        $banner = VendorBanner::findOrFail($request->data_id);
        if($banner) {
            $banner->status = 'inactive';
            $banner->save();
            if ($banner->save) {
                return json_encode(['status' => true, 'message' => 'Success']);
            }
        }
        return json_encode(['status' => false, 'message' => 'Failed']);
    }
}
