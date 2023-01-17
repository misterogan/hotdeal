<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\OrderHistory;
use App\Product;
use App\ProductDetail;
use App\PromotionBanners;
use App\PromotionVoucher;
use App\PromotionVoucherProduct;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class PromotionController extends Controller
{
    public function index(Request $request){
        return view('admin.promotion.index');
    }

    public function create_view(Request $request){
        $id = Auth::user()->id;
        $users = User::orderBy('id')->get();
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();
        $voucher_categories = Category::where('status' ,'active')->where('role', 'promotion')->get();

        return view('admin.promotion.create', compact('id', 'users', 'products', 'categories', 'voucher_categories'));
    }

    public function edit_view(Request $request) {
        $id = Auth::user()->id;
        $code = $request->code;
        $data = PromotionVoucher::where('voucher_code', $code)->first();
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();
        $voucher_categories = Category::where('status' ,'active')->where('role', 'promotion')->get();
        $selected_products = PromotionVoucherProduct::where('promotion_vouchers_id', $data->id)->where('status', 'active')->pluck('product_id')->toArray();
        return view('admin.promotion.edit', compact('id', 'code', 'data', 'products', 'selected_products', 'categories','voucher_categories'));
    }

    public function promotion_dt() {
        $id = Auth::user()->id;
        $promotion = PromotionVoucher::where('status', '!=', 'deleted')->orderByDesc('created_at');

        return DataTables::of($promotion->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'voucher_name' => 'required',
            'voucher_description' => 'required',
            'voucher_category' => 'required',
            'minimum_payment' => 'required',
            'maximum_promo' => 'required',
            'value_discount' => 'required',
            'discount_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image' =>' required|mimes:jpg,bmp,png,jpeg',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        $file_name = uniqid();
        $file = $request->image;
        $filename = $file_name .'-promotion' .time(). '.' . $file->getClientOriginalExtension();
        $destinationPath = '/upload/promotion/';
        $upload = Utils::upload_image($destinationPath, $file, $filename);

        $apply_to_all_product = $request->apply_to_all_product ? true : false;
        $apply_to_all_user = $request->apply_to_all_user ? true : false;
        $show_only = $request->show_only ? true : false;
        $is_code = $request->is_code ? true : false;
        $is_multiple = $request->is_multiple ? true : false;
        $amount_product_only = $request->amount_product_only ? true : false;

        if($upload) {
            if ($apply_to_all_user) {
                $promotion = PromotionVoucher::create([
                    'voucher_name' => $request->voucher_name,
                    'voucher_description' => $request->voucher_description,
                    'voucher_type' => $request->voucher_type,
                    'vendor_id' => $user->id,
                    'voucher_code' => $this->generate_code(),
                    'total' => $request->total_voucher,
                    'is_code' => $is_code,
                    'is_multiple' => $is_multiple,
                    'amount_product_only' => $amount_product_only,
                    'minimum_payment' => $request->minimum_payment,
                    'maximum_promo' => $request->maximum_promo,
                    'discount_type' => $request->discount_type,
                    'value_discount' => $request->value_discount,
                    'category_promotion_id' => $request->voucher_category,
                    'status' => $request->status,
                    'start_date' => date('Y-m-d H:i:s' , strtotime($request->start_date)),
                    'end_date' => date('Y-m-d H:i:s' , strtotime($request->end_date)),
                    'image' => $upload,
                    'apply_to_all_product' => $apply_to_all_product,
                    'apply_to_all_user' => $apply_to_all_user,
                    'show_only' => $show_only,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => $user->name,
                    'updated_by' => $user->name,
                ]);

                if (!$apply_to_all_product && $request->product_selection > 0) {
                    foreach ($request->product_selection as $product_id) {
                        PromotionVoucherProduct::create([
                            'promotion_vouchers_id' => $promotion->id,
                            'product_id' => $product_id,
                            'status' => 'active',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_by' => $user->name,
                            'updated_by' => $user->name,
                        ]);
                    }
                }
            } else {
                if (strlen($request->user_ids) > 0) {
                    $user_ids = array_map('trim', explode(',', $request->user_ids));
                    foreach ($user_ids as $user_id) {
                        $promotion = PromotionVoucher::create([
                            'voucher_name' => $request->voucher_name,
                            'voucher_description' => $request->voucher_description,
                            'voucher_type' => $request->voucher_type,
                            'vendor_id' => $user->id,
                            'voucher_code' => $this->generate_code(),
                            'total' => $request->total_voucher,
                            'is_code' => $is_code,
                            'is_multiple' => $is_multiple,
                            'amount_product_only' => $amount_product_only,
                            'is_minimum' => $is_multiple,
                            'minimum_payment' => $request->minimum_payment,
                            'maximum_promo' => $request->maximum_promo,
                            'discount_type' => $request->discount_type,
                            'value_discount' => $request->value_discount,
                            'status' => 'active',
                            'start_date' => date('Y-m-d H:i:s' , strtotime($request->start_date)),
                            'end_date' => date('Y-m-d H:i:s' , strtotime($request->end_date)),
                            'image' => $upload,
                            'apply_to_all_product' => $apply_to_all_product,
                            'apply_to_all_user' => $apply_to_all_user,
                            'user_id' => $user_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_by' => $user->name,
                            'updated_by' => $user->name,
                        ]);

                        if (!$apply_to_all_product && $request->product_selection > 0) {
                            foreach ($request->product_selection as $product_id) {
                                PromotionVoucherProduct::create([
                                    'promotion_vouchers_id' => $promotion->id,
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
                }
            }

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
            'code' => 'required',
            'voucher_name' => 'required',
            'voucher_description' => 'required',
            'minimum_payment' => 'required',
            'maximum_promo' => 'required',
            'value_discount' => 'required',
            'discount_type' => 'required',
            'start_date' => 'required',
            'voucher_category' => 'required',
            'end_date' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        $code = $request->code;
        $promotion_voucher = PromotionVoucher::where('voucher_code', $code)->first();
        $promotion_voucher_product = PromotionVoucherProduct::where('promotion_vouchers_id', $promotion_voucher->id)->pluck('product_id')->toArray();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name .'-promotion' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/promotion/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload) {
                return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
            }
        } else {
            $upload = $promotion_voucher->image;
        }

        $apply_to_all_product = $request->apply_to_all_product ? true : false;
        $show_only = $request->show_only ? true : false;
        $is_code = $request->is_code ? true : false;
        $is_multiple = $request->is_multiple ? true : false;
        $amount_product_only = $request->amount_product_only ? true : false;

        $promotion = PromotionVoucher::where('voucher_code', $code)->update([
            'voucher_name' => $request->voucher_name,
            'voucher_description' => $request->voucher_description,
            'voucher_type' =>  $request->voucher_type,
            'vendor_id' => $user->id,
            'minimum_payment' => $request->minimum_payment,
            'maximum_promo' => $request->maximum_promo,
            'total' => $request->total_voucher,
            'is_code' => $is_code,
            'is_multiple' => $is_multiple,
            'amount_product_only' => $amount_product_only,
            'discount_type' => $request->discount_type,
            'value_discount' => $request->value_discount,
            'category_promotion_id' => $request->voucher_category,
            'status' => $request->status,
            'start_date' => date('Y-m-d H:i:s' , strtotime($request->start_date)),
            'end_date' => date('Y-m-d H:i:s' , strtotime($request->end_date)),
            'image' => $upload,
            'apply_to_all_product' => $apply_to_all_product,
            'show_only' => $show_only,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if (!$apply_to_all_product && $request->product_selection > 0) {
            $different_ids = array_diff($promotion_voucher_product, $request->product_selection);
            foreach ($different_ids as $product_id) {
                $query = PromotionVoucherProduct::where('promotion_vouchers_id', $promotion_voucher->id)->where('product_id', $product_id)->first();
                if ($query) {
                    // PromotionVoucherProduct::where('promotion_vouchers_id', $promotion_voucher->id)->where('product_id', $product_id)->delete();
                    $query->status = 'inactive';
                    $query->save();
                }
            }

            $different_ids_2 = array_diff($request->product_selection, $promotion_voucher_product);
            foreach ($different_ids_2 as $product_id) {
                $query = PromotionVoucherProduct::where('promotion_vouchers_id', $promotion_voucher->id)->where('product_id', $product_id)->first();
                if (!$query) {
                    PromotionVoucherProduct::create([
                        'promotion_vouchers_id' => $promotion_voucher->id,
                        'product_id' => $product_id,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => $user->name,
                        'updated_by' => $user->name,
                    ]);
                }
            }
        } else {
            PromotionVoucherProduct::where('promotion_vouchers_id', $promotion_voucher->id)->update([
                'status' => 'inactive'
            ]);
        }

        if ($promotion > 0) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> ['Update failed']]);
        }
    }

    function generate_code() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

    public function banner_view(){
        $banner = PromotionBanners::where('id', 1)->first();
        if($banner != null){
            return view('admin.promotion.banner_view', compact('banner'));
        } else {
            PromotionBanners::create([
                'id' => 1,
                'banner' => '',
                'created_by' => 'Admin',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_by' => 'Admin'
            ]);
            return view('admin.promotion.banner_view', compact('banner'));
        }
    }

    public function master_banner(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'banner' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        if ($request->hasFile('banner')) {
            $file_name = uniqid();
            $file = $request->banner;
            $filename = $file_name .'-promotion-vouchers-banner' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/promotions/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else {
            $upload = null;
        }

        $banner = PromotionBanners::where('id', 1)->update([
            'banner' => $upload,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if ($banner > 0) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function delete_image(Request $request){
        // dd($request->data_id);
        $id = $request->data_id;
        PromotionVoucher::where('id', $id)->update([
            'image' => ''
        ]);
    }

    public function delete_banner(Request $request){
        // dd($request->data_id);
        $id = $request->data_id;
        // dd($id);
        PromotionBanners::where('id', $id)->update([
            'banner' => ''
        ]);
    }
}
