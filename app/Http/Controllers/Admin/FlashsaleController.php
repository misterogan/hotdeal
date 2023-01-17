<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\FlashSale;
use App\FlashSaleDetail;
use App\FlashsaleLog;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Product;
use App\PromotionVoucher;
use App\PromotionVoucherProduct;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class FlashsaleController extends Controller
{
    public function index(Request $request){
        return view('admin.flashsale.index');
    }

    public function create_view(Request $request) {
        $id = Auth::user()->id;
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();

        return view('admin.flashsale.create', compact('id', 'products', 'categories'));
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $data = FlashSale::where('id', $id)->first();
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();
        $selected_products = FlashSaleDetail::where('flash_sale_id', $id)->pluck('product_id')->toArray();
        $logs = FlashsaleLog::where('flashsale_id', $data->id)->orderByDesc('id')->get();
        return view('admin.flashsale.edit', compact('id', 'data', 'products', 'selected_products', 'categories', 'logs'));
    }

    public function log_view($id){
        $log = FlashsaleLog::where('id', $id)->first();
        $before = json_decode($log->before, true);
        $after = json_decode($log->after, true);
        if($log->type == 'created'){
            $after = $before;
        }
        $beforeProducts = [];
        foreach ($before['products'] as $val) {
            $prod = Product::where('id', $val)->pluck('name')->first();
            array_push($beforeProducts, $prod);
        }
        $afterProducts = [];
        foreach ($after['products'] as $val) {
            $prod = Product::where('id', $val)->pluck('name')->first();
            array_push($afterProducts, $prod);
        }
        // dd($beforeProducts);
        // dd($before['detail'], $after);
        return view('admin.flashsale.log', compact('log', 'after', 'before', 'beforeProducts', 'afterProducts'));
    }

    public function flashsale_dt() {
        $flashsale = FlashSale::where('status','active')->orderByDesc('created_at');

        return DataTables::of($flashsale->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'banner_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'banner' =>' mimes:jpg,bmp,png,jpeg,webp',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $eligible = FlashSale::where('end_date', '>', $request->start_date)->where('start_date', '<', $request->end_date)->where('status', 'active')->count();
        if ($eligible > 0) {
            return json_encode(['status' => false, 'message' => 'Cannot create flash sale at this period of time, please check again. (start and end time should not collide with other active flash sale events.']);
        }

        $user = Auth::user();
        $file_name = uniqid();
        $file = $request->banner;
        $filename = $file_name .'-flashsale' .time(). '.' . $file->getClientOriginalExtension();
        $destinationPath = '/upload/flashsale/';
        $upload = Utils::upload_image($destinationPath, $file, $filename);
        // $file_name1 = uniqid();
        // $mobile = $request->banner_mobile;
        // $filename1 = $file_name1 .'-flashsale' .time(). '.' . $file->getClientOriginalExtension();
        // $uploadBanner = Utils::upload_image($destinationPat
        if($upload) {
            $flashsale = FlashSale::create([
                'name' => $request->name,
                'banner_type' => $request->banner_type,
                'banner' =>  $upload,
                // 'banner_mobile' =>  $uploadBanner,
                'status' => 'active',
                'start_date' => date('Y-m-d H:i:s' , strtotime($request->start_date)),
                'end_date' => date('Y-m-d H:i:s' , strtotime($request->end_date)),
                'slug' => str_replace(' ', '-', strtolower($request->name)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => $user->name,
                'updated_by' => $user->name,
            ]);

            if ($request->product_selection > 0) {
                foreach ($request->product_selection as $product_id) {
                    $products = FlashSaleDetail::create([
                        'flash_sale_id' => $flashsale->id,
                        'product_id' => $product_id,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => $user->name,
                        'updated_by' => $user->name,
                    ]);
                }
            }
            $log['detail'] = $flashsale;
            $log['products'] = $products;
            $this->create_log($flashsale->id, $log, $log, 'created');
            

            $status = true;
            $message = "Success";
        } else {
            $status = false;
            $message = "Failed to upload file";
        }

        return json_encode(['status'=> $status, 'message'=> $message]);
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'banner_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        
        $id = $request->id;
        $flash_sale = FlashSale::where('id', $id)->first();
        $flash_sale_details = FlashSaleDetail::where('flash_sale_id', $id)->pluck('product_id')->toArray();
        
        $before['detail'] = $flash_sale->toArray();
        $before['products'] = $flash_sale_details;

        if ($request->hasFile('banner')) {
            $file_name = uniqid();
            $file = $request->banner;
            $filename = $file_name .'-flashsale' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/flashsale/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else {
            $upload = $flash_sale->banner;
        }

        // if ($request->hasFile('banner_mobile')) {
        //     $file_name = uniqid();
        //     $file = $request->banner_mobile;
        //     $filename = $file_name .'-flashsale' .time(). '.' . $file->getClientOriginalExtension();
        //     $destinationPath = '/upload/flashsale/';
        //     $uploadBanner = Utils::upload_image($destinationPath, $file, $filename);
        //     if (!$uploadBanner) {
        //         return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
        //     }
        // } else {
        //     $uploadBanner = $flash_sale->banner_mobile;
        // }

        $flashsale = FlashSale::where('id', $id)->update([
            'name' => $request->name,
            'banner_type' => $request->banner_type,
            'banner' =>  $upload,
            // 'banner_mobile' => $uploadBanner,
            'status' => $request->status,
            'start_date' => date('Y-m-d H:i:s' , strtotime($request->start_date)),
            'end_date' => date('Y-m-d H:i:s' , strtotime($request->end_date)),
            'slug' => str_replace(' ', '-', strtolower($request->name)),
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if ($request->product_selection > 0) {
            $different_ids = array_diff($flash_sale_details, $request->product_selection);
            foreach ($different_ids as $product_id) {
                $query = FlashSaleDetail::where('flash_sale_id', $id)->where('product_id', $product_id)->first();
                if ($query) {
                    FlashSaleDetail::where('flash_sale_id', $id)->where('product_id', $product_id)->delete();
                }
            }

            $different_ids_2 = array_diff($request->product_selection, $flash_sale_details);
            foreach ($different_ids_2 as $product_id) {
                $query = FlashSaleDetail::where('flash_sale_id', $id)->where('product_id', $product_id)->first();
                if (!$query) {
                    FlashSaleDetail::create([
                        'flash_sale_id' => $id,
                        'product_id' => $product_id,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => $user->name,
                        'updated_by' => $user->name,
                    ]);
                }
            }
        }
        $after_detail = FlashSale::where('id', $id)->first();
        $after_products = FlashSaleDetail::where('flash_sale_id', $id)->pluck('product_id')->toArray();
        
        $after['detail'] = $after_detail->toArray();
        $after['products'] = $after_products;

        $this->create_log($id, $before, $after, 'updated');

        if ($flashsale > 0) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> 'Update failed']);
        }
    }

    public function create_log($id, $before, $after, $type){
        FlashsaleLog::create([
            'flashsale_id' => $id,
            'before' => json_encode($before),
            'after' => json_encode($after),
            'type' => $type,
            'updated_by' => Auth::user()->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function delete_image(Request $request){
        // dd($request->data_id);
        $id = $request->data_id;

        FlashSale::where('id', $id)->update([
            'banner' => '',
            'banner_mobile' => ''
        ]);
    }
}
