<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductDetail;
use App\PromotionDiscount;
use App\PromotionDiscountProduct;
use App\PromotionVoucher;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use function Webmozart\Assert\Tests\StaticAnalysis\integer;

class DiscountController extends Controller
{
    public function index(Request $request) {
        return view('admin.discount.index');
    }

    public function create_view(Request $request) {

        $id = Auth::user()->id;
        $promotion_vendor_id = PromotionDiscount::where('status', 'active')->pluck('vendor_id')->all();
        $vendors = Vendor::whereNotIn('id', $promotion_vendor_id)->get();
        $discounts = PromotionDiscount::where('vendor_id', $id)->get();
        $products = Product::with('details')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->with('products.details')->get();
        return view('admin.discount.create', compact('id', 'vendors', 'discounts', 'products', 'categories'));

    }

    public function edit_view(Request $request) {

        $id = $request->id;
        $vendors = Vendor::get();
        $data = PromotionDiscount::where('id', $request->id)->with('discount_products')->first();
        $products = Product::where('vendor_id', $data->vendor_id)->with('details')->with('category')->orderBy('name')->get();
        return view('admin.discount.edit', compact('id', 'data', 'vendors', 'products'));
    }

    public function set_discount_dt(Request $request) {
        $product = Product::where('vendor_id', $request->vendor_id)->where('products.status','active')->with('detail_hasone')->with('discount')->with('promotion')->orderBy('id');
        return DataTables::of($product->get())->addIndexColumn()->make(true);
    }

    public function discount_dt() {
        $id = Auth::user()->id;
        $discount = PromotionDiscount::where('promotion_discounts.status','!=','deleted')->with('vendor')->orderBy('updated_at', 'desc');

        return DataTables::of($discount->get())->addIndexColumn()->make(true);
    }

    public function set_discount(Request $request) {
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'product_id' => 'required',
            'value_discount' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        $promotion_discount_id = PromotionDiscount::where('vendor_id', $request->vendor_id)->where('status', 'active')->value('id');
        $admin_fee = Product::where('id', $request->product_id)->whereIn('status' , ['active','inactive'])->value('admin_fee');
        $price = ProductDetail::where('product_id', $request->product_id)->whereIn('status' , ['active','inactive'])->value('price');
        $fix_price = $admin_fee + $price;
        $value = (int)$request->value_discount;
        if($value > 100) {
            $total_discount = (($fix_price - $value) / $fix_price) * 100;
            $val_discount = $request->value_discount;
            $type = "nominal";
        } else {
            $total_discount = $request->value_discount;
            $val_discount = $fix_price - (($fix_price * $total_discount)/100);
            $type = "percentage";
        }

        // dd(substr(round($total_discount), 0));
        $response_discount = Utils::currency_convert(($fix_price * $total_discount)/100);
        $response_price = Utils::currency_convert($fix_price - (($fix_price * $total_discount)/100));
        // $response_discount = substr(round($total_discount), 0);
        

        $discount = PromotionDiscountProduct::updateOrCreate(
            ['vendor_id' => $request->vendor_id, 'product_id' => $request->product_id],
            ['promotion_discounts_id' => $promotion_discount_id, 'value_discount' => $fix_price - $val_discount, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'created_by' => $user->name, 'updated_by' => $user->name,]
        );

        if(!$discount->wasRecentlyCreated && !$discount->wasChanged()){
            return json_encode(['status'=> false, 'message'=> ['Failed to update discount value']]);
        }

        return json_encode(['status'=> true, 'message'=> 'Success' , 'total_discount' => $response_discount , 'price' => $response_price, 'nominal' => $val_discount, 'percentage' => ceil($total_discount), 'type' => $type]);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();

        $discount = PromotionDiscount::create([
            'promo_from' => 'hotdeal',
            'vendor_id' => $request->vendor,
            'status' => 'active',
            // 'type' => $request->type,
            'start_date' => date('Y-m-d H:i:s', strtotime($request->start_date)),
            'end_date' => date('Y-m-d H:i:s', strtotime($request->end_date)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        return json_encode(['status'=> true, 'message'=> 'Success', 'id' => $discount->id]);
    }

    public function edit(Request $request) {

        $validation = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        $id = $request->id;
        try {
            DB::beginTransaction();
            $discount = PromotionDiscount::where('id', $id)->update([
                'status' => $request->status,
                'type' => $request->type,
                'start_date' => date('Y-m-d H:i:s', strtotime($request->start_date)),
                'end_date' => date('Y-m-d H:i:s', strtotime($request->end_date)),
                'updated_at' => Carbon::now(),
                'updated_by' => $user->name,
            ]);

            PromotionDiscountProduct::where('vendor_id', $request->vendor_id)->update([
                'type' => $request->type
            ]);

            PromotionVoucher::where('vendor_id', $request->vendor_id)->update([
                'discount_type' => $request->type == 'percentage' ? 'percent' :'percent'
            ]);
            DB::commit();
            //return $this->successResponse([] , 'Berhasil mengubah produk');
        }catch (Throwable $e) {
            DB::rollback();
            return json_encode(['status'=> false, 'message'=> $e->getMessage()]);
        }
        return json_encode(['status'=> true, 'message'=> 'Success']);
    }
}
