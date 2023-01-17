<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Exports\MassProductExcel;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\LogUpdateProduct;
use App\Product;
use App\ProductDetail;
use App\ProductGallery;
use App\ProductUploadFileLog;
use App\PromotionVoucher;
use App\PromotionVoucherProduct;
use App\Vendor;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    public function index(Request $request){
        return view('admin.product.index');
    }

    public function log_product_dt(Request $request) {
        $product_id = $request->product_id;
        $logs = LogUpdateProduct::with('product')->where('product_id', $product_id)->get();
        return DataTables::of($logs)->addIndexColumn()->make(true);
    }

    public function changes_view($id) {
        $log = LogUpdateProduct::where('id', $id)->first();
        $before = json_decode($log->before, true);
        $after = json_decode($log->after, true);

        //Old (Before)
        $oldGalleries = [];
        $oldVideo = [];
        if(isset($before['galleries'])){
            $oldGalleries = $before['galleries'];
            $oldVideo = collect($before['galleries'])->where('type', '2')->first();
        }
        $oldProduct = $before['product'];
        $oldDetailProduct = $before['detail'];
        $old_have_variant = count($oldDetailProduct) >1 ? true : false;
        $oldVendor = Vendor::where('id', $before['product']['vendor_id'])->first()->name;
        $oldCategory = Category::where('id', $before['product']['category_id'])->first()->category;
        
        // Changes (After)
        $afterGalleries = [];
        $afterVideo = [];
        if(isset($after['galleries'])){
            $afterGalleries = $after['galleries'];
            $afterVideo = collect($after['galleries'])->where('type', '2')->first();
        }
        $afterProduct = $after['product'];
        $afterDetailProduct = $after['detail'];
        // $afterGalleries = $after['galleries'];
        // $afterVideo = collect($after['galleries'])->where('type', '2')->first();
        $after_have_variant = count($afterDetailProduct) >1 ? true : false;
        $afterVendor = Vendor::where('id', $after['product']['vendor_id'])->first()->name;
        $afterCategory = Category::where('id', $after['product']['category_id'])->first()->category;

        return view('admin.product.changes', compact('log', 'oldProduct', 'oldDetailProduct', 'oldGalleries', 'oldVideo', 'old_have_variant', 'afterProduct', 'afterDetailProduct', 'afterGalleries', 'afterVideo', 'after_have_variant', 'oldVendor', 'afterVendor', 'oldCategory', 'afterCategory'));
    }

    public function create_view(Request $request){
        $products = Product::with('category')->orderBy('name')->get();
        $categories = Category::where('is_parent', true)->where('status', 'active')->orderBy('category')->get();

        $vendors = Vendor::orderBy('name')->get();

        return view('admin.product.create', compact('products', 'categories', 'vendors'));
    }

    public function edit_view(Request $request) {
        $product_id = $request->id;
        $product = Product::where('id', $product_id)->with(['details' => function($query){
            $query->where('status', 'active');
        }])->with(['images' => function($query){
            $query->where('status', 'active');
        }])->with('video')->with('category')->first();
        $categories = Category::where('is_parent', true)->where('status', 'active')->orderBy('category')->get();
        $vendors = Vendor::orderBy('name')->get();
        $is_multiple_variants = false;
        foreach($product->details as $detail) {
            if ($detail->variant_key_2 != null) {
                $is_multiple_variants = true;
            }
        }
        $description = '<p></p>';
        if($product){
            $product->details_variant = $this->format_details($product->details);
            $product->variant = $this->format_variant($product->details);
            $description = $product->description;
            unset($product->description);
        }


        //echo json_encode($product);exit;
        $variant_images = ProductGallery::where('product_id',$product_id)->whereNotNull('product_detail_id')->Where('product_detail_id','!=','0')->whereNotNull('product_variant_image')->get();
        return view('admin.product.edit', compact('product_id', 'product', 'categories', 'vendors', 'is_multiple_variants', 'variant_images', 'description'));
    }

    public function product_dt() {
        $id = Auth::user()->id;
        $products = Product::with('category')->with('vendor')->where('products.status','!=','deleted')->orderByDesc('created_at')->get();
        foreach ($products as $value) {
            $stock = ProductDetail::where('product_id', $value->id)->where('status', 'active')->where('status', '!=', 'delete')->sum('stock');
            $value->setAttribute('stock', $stock);
        }
        return DataTables::of($products)->addIndexColumn()->make(true);
    }

    public function variant_image(Request $request){
        if($request->hasFile('image')){
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $url = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if($url){
                return json_encode(['status' => 'success', 'message' => 'Insert product failed.' , 'url' => $url]);
            }
        }
        return json_encode(['status' => 'errro', 'message' => 'Upload data failed.' , 'url' => '']);
    }

    public function delete_image(Request $request){
        $image_id = $request->image_id;
        ProductGallery::where('id', $image_id)->update([
            'status' => 'inactive'
        ]);

    }

    private function format_details($data){
        $result = [];
        foreach($data as $item){
            $result[$item->variant_value_1.'_'.$item->variant_value_2] = [
                'price' => $item->price,
                'stock' => $item->stock,
                'sku' => $item->child_sku
            ];
        }
        return $result;
    }

    private function format_variant($data){
        $variant = [
            'variant_1' => [
                'label' => '',
                'variant' => []
            ],
            'variant_2' => [
                'label' => '',
                'variant' => []
            ]
        ];
        $v_v1 = [];
        $v_v2 = [];
        if(count($data) > 0){
            foreach($data as $k=>$v){
                if($v['variant_key_1'] != null){
                    $variant_pic = ProductGallery::where('status', 'active')->where('product_id' , $v->product_id)->where('product_variant_image', $v['variant_value_1'])->pluck('link')->first();
                    $variant['variant_1']['label'] = $v['variant_key_1'];
                    if(!in_array( $v['variant_value_1'], $v_v1)){
                        $v_v1[] = $v['variant_value_1'];
                        $variant['variant_1']['variant'][] = [
                                    'price' => $v['price'],
                                    'stock' => $v['stock'],
                                    'option' => $v['variant_value_1'],
                                    'picture' => $variant_pic,
                                    'product_detail_id' => $v['id']
                        ];
                    }
                }
                if($v['variant_key_2'] != null){
                    $variant['variant_2']['label'] = $v['variant_key_2'];
                    if(!in_array( $v['variant_value_2'], $v_v2)){
                        $v_v2[] = $v['variant_value_2'];
                        $variant['variant_2']['variant'][] = [
                            'price' => $v['price'],
                            'stock' => $v['stock'],
                            'option' => $v['variant_value_2'],
                        ];
                    }
                }

            }
        }
        return $variant;
    }

    public function edit(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'name' => 'required',
            'category_selection' => 'required',
            'brand' => 'required',
            'description' => 'required',
            'option' => 'required',
            'status' => 'required',
        ]);
        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $images = [];
        $product_id = $request->product_id;
        $detail_id = 0;
        if($request->short_desc == "") {
            $short_desc = substr(strip_tags($request->description), 0, 255);
        } else {
            if(strlen(strip_tags($request->short_desc)) >= 255){
                return json_encode(['status'=> false, 'message'=> ['Short description harus kurang dari 255 karakter!']]);
            }
            $short_desc = strip_tags($request->short_desc);
        }

        $current = Product::where('id', $product_id)->first();
        $current_detail = ProductDetail::where('product_id', $product_id)->where('status', 'active')->get();
        $current_galleries = ProductGallery::where('product_id', $product_id)->where('status', 'active')->get();

        $product = Product::where('id', $product_id)->update([
            'name' => $request->name,
            'vendor_id' => $request->vendor_id,
            'category_id' => $request->category_selection,
            'brand' => $request->brand,
            'description' => $request->description,
            'short_desc'   => $short_desc,
            'admin_fee' => $request->admin_fee,
            'weight' => $request->weight,
            'height' => $request->height,
            'length' => $request->length,
            'width' => $request->width,
            'dimension' => $request->dimension,
            'sku' => $request->sku,
            'slug' => Utils::slugify($request->name),
            'status' => $request->status,
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::user()->name,
        ]);

        // log before
        $before['product'] = $current->toArray();
        $before['detail'] = $current_detail->toArray();
        $before['galleries'] = $current_galleries->toArray();

        try {
            DB::beginTransaction();
            if (!$product) {
                return json_encode(['status' => false, 'message' => 'Insert product failed.']);
            }

            ProductDetail::where('product_id' , $product_id)->update([
                'status' => 'inactive'
            ]);

            ProductGallery::where('product_id' , $product_id)->whereNotNull('product_variant_image')->update([
                'status' => 'inactive'
            ]);

            if ($request->option == 'single') {
                    $details_product = ProductDetail::Where('product_id' , $product_id)->first();
                    $details_product->stock = $request->stock;
                    $details_product->price = $request->price;
                    $details_product->status = 'active';
                    $details_product->updated_at = Carbon::now();
                    $details_product->updated_by = Auth::user()->name;
                    $details_product->variant_key_1 ='';
                    $details_product->variant_key_2 ='';
                    $details_product->variant_value_1 ='';
                    $details_product->variant_value_2 ='';
                    $details_product->save();

            } else {
                $varian_1 = json_decode($request->variant_1 , true);
                $varian_2 = json_decode($request->variant_2 , true);
                $details_variant = json_decode($request->details_variant , true);

                if(count($varian_1['variant']) > 0){
                    foreach($varian_1['variant'] as $v1){
                        if(count($varian_2['variant']) > 0 ){
                            foreach($varian_2['variant'] as $v2){
                                $details = ProductDetail::where('product_id' , $product_id)
                                        ->where('variant_key_1' , $varian_1['label'])
                                        ->where('variant_value_1' , $v1['option'])
                                        ->where('variant_key_2' , $varian_2['label'])
                                        ->where('variant_value_2' , $v2['option'])
                                        ->first();
                                if($details){
                                    $details->stock = $details_variant[$v1['option'].'_'.$v2['option']]['stock'];
                                    $details->price = $details_variant[$v1['option'].'_'.$v2['option']]['price'];
                                    $details->variant_key_1 = $varian_1['label'];
                                    $details->variant_value_1 =  $v1['option'];
                                    $details->variant_key_2 = $varian_2['label'];
                                    $details->variant_value_2 =  $v2['option'];
                                    $details->child_sku = $details_variant[$v1['option'].'_'.$v2['option']]['sku'];
                                    $details->status = 'active';
                                    $details->updated_at = Carbon::now();
                                    $details->updated_by = Auth::user()->name;
                                    $details->save();
                                }else{
                                    ProductDetail::create([
                                        'product_id' => $product_id,
                                        'stock' => $details_variant[$v1['option'].'_'.$v2['option']]['stock'],
                                        'price' => $details_variant[$v1['option'].'_'.$v2['option']]['price'],
                                        'variant_key_1' => $varian_1['label'],
                                        'variant_value_1' =>$v1['option'],
                                        'variant_key_2' => $varian_2['label'],
                                        'variant_value_2' =>$v2['option'],
                                        'child_sku' => $details_variant[$v1['option'].'_'.$v2['option']]['sku'],
                                        'status' => 'active',
                                        'created_at' => Carbon::now(),
                                        'updated_at' => Carbon::now(),
                                        'created_by' => Auth::user()->name,
                                        'updated_by' => Auth::user()->name,
                                    ]);
                                }

                            }
                        }else{
                            $details = ProductDetail::where('product_id' , $product_id)
                                            ->where('variant_key_1' , $varian_1['label'])
                                            ->where('variant_value_1' , $v1['option'])
                                            ->first();
                            if($details){
                                $details->stock = $details_variant[$v1['option'].'_']['stock'];
                                $details->price = $details_variant[$v1['option'].'_']['price'];
                                $details->variant_key_1 = $varian_1['label'];
                                $details->variant_value_1 =  $v1['option'];
                                $details->variant_key_2 = null;
                                $details->variant_value_2 =  null;
                                $details->child_sku = $details_variant[$v1['option'].'_']['sku'];
                                $details->status = 'active';
                                $details->updated_at = Carbon::now();
                                $details->updated_by = Auth::user()->name;
                                $details->save();
                            }else{
                                $var_1_data = ProductDetail::create([
                                    'product_id' => $product_id,
                                    'stock' => $details_variant[$v1['option'].'_']['stock'],
                                    'price' => $details_variant[$v1['option'].'_']['price'],
                                    'variant_key_1' => $varian_1['label'],
                                    'variant_value_1' =>$v1['option'],
                                    'variant_key_2' => null,
                                    'variant_value_2' =>null,
                                    'child_sku' => $details_variant[$v1['option'].'_']['sku'],
                                    'status' => 'active',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'created_by' => Auth::user()->name,
                                    'updated_by' => Auth::user()->name,
                                ]);
                            }
                        }
                        if($v1['picture'] != ''){
                            $variant_gallery = ProductGallery::where('product_variant_image' , $v1['option'])->where('product_id' , $product_id)->first();
                            if(!$variant_gallery){
                                //insert image
                                ProductGallery::create([
                                                'product_id' => $product_id,
                                                'product_detail_id' => null,
                                                'type' => 1,
                                                'product_variant_image' =>$v1['option'],
                                                'link' => $v1['picture'],
                                                'status' => 'active',
                                                'created_at' => Carbon::now(),
                                                'updated_at' => Carbon::now(),
                                                'created_by' => Auth::user()->name,
                                                'updated_by' => Auth::user()->name,
                                            ]);
                            }else{
                                //update image variant
                                $variant_gallery->link = $v1['picture'];
                                $variant_gallery->product_variant_image = $v1['option'];
                                $variant_gallery->updated_by = Auth::user()->name;
                                $variant_gallery->updated_at = Carbon::now();
                                $variant_gallery->status = 'active';
                                $variant_gallery->save();
                            }
                        }

                    }
                }
            }
            // Check image & video
            if ($request->hasFile('image_1')) {
                $file_name = uniqid();
                $file = $request->image_1;
                $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file );
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }
                if ($request->image_1_id != "") {
                    $gallery = ProductGallery::where('id', $request->image_1_id)->update([
                        'link' => $upload,
                        'status' => 'active',
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 1,
                        'link' => $upload,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }
            if ($request->hasFile('image_2')) {
                $file_name = uniqid();
                $file = $request->image_2;
                $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file );
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }
                if ($request->image_2_id != "") {
                    $gallery = ProductGallery::where('id', $request->image_2_id)->update([
                        'link' => $upload,
                        'status' => 'active',
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 1,
                        'link' => $upload,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }
            if ($request->hasFile('image_3')) {
                $file_name = uniqid();
                $file = $request->image_3;
                $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file );
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }
                if ($request->image_3_id != "") {
                    $gallery = ProductGallery::where('id', $request->image_3_id)->update([
                        'link' => $upload,
                        'status' => 'active',
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 1,
                        'link' => $upload,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }
            if ($request->hasFile('image_4')) {
                $file_name = uniqid();
                $file = $request->image_4;
                $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file );
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }
                if ($request->image_4_id != "") {
                    $gallery = ProductGallery::where('id', $request->image_4_id)->update([
                        'link' => $upload,
                        'status' => 'active',
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 1,
                        'link' => $upload,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }
            if ($request->hasFile('image_5')) {
                $file_name = uniqid();
                $file = $request->image_5;
                $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file );
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }
                if ($request->image_5_id != "") {
                    $gallery = ProductGallery::where('id', $request->image_5_id)->update([
                        'link' => $upload,
                        'status' => 'active',
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 1,
                        'link' => $upload,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }
            if ($request->hasFile('image_6')) {
                $file_name = uniqid();
                $file = $request->image_6;
                $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file );
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }
                if ($request->image_6_id != "") {
                    $gallery = ProductGallery::where('id', $request->image_6_id)->update([
                        'link' => $upload,
                        'status' => 'active',
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 1,
                        'link' => $upload,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }
            // dd($request->delete_video);
            if ($request->delete_video == "true"){
                $videoProduct = ProductGallery::where('product_id', $product_id)->where('type', 2)->where('status', '!=', 'inactive')->first();
                if($videoProduct){
                    $videoProduct->status = 'inactive';
                    $videoProduct->save();
                }
            }
            if ($request->hasFile('video')) {
                $file_name = uniqid();
                $file = $request->video;
                $filename = $file_name . '-product' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/product/video/';
                $video = Utils::upload_video($destinationPath, $file, $filename, $file->getClientOriginalExtension());

                if (!$video) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload video.']);
                }

                ProductGallery::where('product_id' , $product_id)->where('type' , '2')->update(['status' => 'inactive']);

                if ($request->video_id != "") {
                    $gallery = ProductGallery::where('id', $request->video_id)->update([
                        'link' => $video,
                        'status' => 'active',
                        'type' => 2,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::user()->name,
                    ]);
                } else {
                    $gallery = ProductGallery::create([
                        'product_id' => $product_id,
                        'product_detail_id' => null,
                        'type' => 2,
                        'link' => $video,
                        'status' => 'active',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }

            // remove image
            if(isset($request->deleted_image)){
                $deleted_image = explode(',',$request->deleted_image);
                if(count($deleted_image) > 0){
                    foreach($deleted_image as $image){
                        ProductGallery::where('product_id' , $product_id)->where('link' , $image)->update([
                            'status' => 'inactive' , 
                            'updated_at' => Carbon::now() , 
                            'updated_by' => Auth::user()->name
                        ]);
                    }
                }
            }

            $after_product = Product::where('id', $product_id)->first();
            $after_detail_product = ProductDetail::where('product_id', $product_id)->where('status', 'active')->get();
            $after_galleries = ProductGallery::where('product_id', $product_id)->where('status', 'active')->get();

            $after['product'] = $after_product->toArray();
            $after['detail'] = $after_detail_product->toArray();
            $after['galleries'] = $after_galleries->toArray();

            LogUpdateProduct::create([
                'product_id' => $product_id,
                'before' => json_encode($before),
                'after' => json_encode($after),
                'updated_by' => Auth::user()->name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
           DB::rollBack();
        }

        Cache::forget("meta_" . Utils::slugify($request->name));

        return json_encode(['status' => true, 'message' => 'Success']);

    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'name' => 'required',
            'category_selection' => 'required',
            'brand' => 'required',
            'description' => 'required',
            'admin_fee' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'length' => 'required',
            'width' => 'required',
            'dimension' => 'required',
            'sku' => 'required',
            'option' => 'required',
            'status' => 'required',
            'image' => 'required',
            // 'image.*' => 'mimes:png,jpg,jpeg,svg,webp',
        ]);


        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        DB::beginTransaction();
        try {
            if($request->short_desc == "") {
                $short_desc = substr(strip_tags($request->description), 0, 255);
            } else {
                if(strlen(strip_tags($request->short_desc)) >= 255){
                    return json_encode(['status'=> false, 'message'=> ['Short description harus kurang dari 255 karakter!']]);
                }
                $short_desc = strip_tags($request->short_desc);
            }
            $product = Product::create([
                'name'          => $request->name,
                'vendor_id'     => $request->vendor_id,
                'category_id'   => $request->category_selection,
                'brand'         => $request->brand,
                'description'   => $request->description,
                'short_desc'   => $short_desc,
                'admin_fee'     => $request->admin_fee,
                'weight'        => $request->weight,
                'height'        => $request->height,
                'length'        => $request->length,
                'width'         => $request->width,
                'dimension'     => $request->dimension,
                'sku'           => $request->sku,
                'slug'          => $request->slug,
                'status'        => $request->status,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'created_by'    => Auth::user()->name,
                'updated_by'    => Auth::user()->name,
            ]);

            foreach($request->file('image') as $key=>$image) {
                $primary = $key == 0 ? true : false;
                
                $file_name = uniqid();
                $filename = $file_name . '-banners' . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = '/upload/banners/';
                $upload = Utils::upload_image($destinationPath, $image, $filename);
                if (!$upload) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }

                ProductGallery::create([
                    'product_id' => $product->id,
                    'product_detail_id' => null,
                    'type' => 1,
                    'link' => $upload,
                    'status' => 'active',
                    'is_primary' => $primary,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);

            }
            if($request->has('video')){

                $file_name = uniqid();
                $file = $request->video;
                $filename = $file_name .'-product' .time(). '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/product/';
                $video = Utils::upload_video($destinationPath, $file, $filename);


                ProductGallery::create([
                    'product_id' => $product->id,
                    'product_detail_id' => null,
                    'type' => 2,
                    'link' => $video,
                    'status' => 'active',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);

            }

            if ($request->option == 'single') {
                $detail = ProductDetail::create([
                    'product_id' => $product->id,
                    'stock' => $request->stock,
                    'price' => $request->price,
                    'status' => ($request->status != 'deleted' ? 'active' : 'inactive'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);
            }else{

                $varian_1 = json_decode($request->variant_1 , true);
                $varian_2 = json_decode($request->variant_2 , true);
                $details_variant = json_decode($request->details_variant , true);

                if(count($varian_1['variant']) > 0){
                    foreach($varian_1['variant'] as $v1){
                        if(count($varian_2['variant']) > 0 ){
                            foreach($varian_2['variant'] as $v2){
                            if (strlen($details_variant[$v1['option'].'_'.$v2['option']]['sku']) > 20) {
                                return json_encode(['status'=> false, 'message'=> ['Variation Code harus di bawah 20 karakter!']]);
                            }
                            ProductDetail::create([
                                'product_id' => $product->id,
                                'stock' => $details_variant[$v1['option'].'_'.$v2['option']]['stock'],
                                'price' => $details_variant[$v1['option'].'_'.$v2['option']]['price'],
                                'variant_key_1' => $varian_1['label'],
                                'variant_value_1' =>$v1['option'],
                                'variant_key_2' => $varian_2['label'],
                                'variant_value_2' =>$v2['option'],
                                'child_sku' => $details_variant[$v1['option'].'_'.$v2['option']]['sku'],
                                'status' => 'active',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name,
                            ]);
                            }
                        }else{
                            if (strlen($details_variant[$v1['option'].'_']['sku']) > 20) {
                                return json_encode(['status'=> false, 'message'=> ['Variation Code harus di bawah 20 karakter!']]);
                            }
                            ProductDetail::create([
                                'product_id' => $product->id,
                                'stock' => $details_variant[$v1['option'].'_']['stock'],
                                'price' => $details_variant[$v1['option'].'_']['price'],
                                'variant_key_1' => $varian_1['label'],
                                'variant_value_1' =>$v1['option'],
                                'variant_key_2' => null,
                                'variant_value_2' =>null,
                                'child_sku' => $details_variant[$v1['option'].'_']['sku'],
                                'status' => 'active',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name,
                            ]);
                        }

                        if($v1['picture'] != ''){
                            ProductGallery::create([
                                'product_id' => $product->id,
                                'product_detail_id' => null,
                                'type' => 1,
                                'product_variant_image' =>$v1['option'],
                                'link' => $v1['picture'],
                                'status' => 'active',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                                'created_by' => Auth::user()->name,
                                'updated_by' => Auth::user()->name,
                            ]);
                        } else {
                            return json_encode(['status'=> false, 'message'=> ['Data Image Variant Tidak Boleh Kosong!']]);
                        }
                    }
                }
            }

            //  Log
            Utils::log_product($product->id, 'created');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode(['status'=> false, 'message'=> $e->getMessage()]);
        }
        return json_encode(['status'=> true, 'message'=> 'Success']);

    }

    public function submit2(Request $request) {
        $validation = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'name' => 'required',
            'category_selection' => 'required',
            'brand' => 'required',
            'description' => 'required',
            // 'admin_fee' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'length' => 'required',
            'width' => 'required',
            'dimension' => 'required',
            'sku' => 'required',
            'option' => 'required',
            'status' => 'required',

        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $images = [];
        $detail_id = 0;

        $product = Product::create([
            'name' => $request->name,
            'vendor_id' => $request->vendor_id,
            'category_id' => $request->category_selection,
            'brand' => $request->brand,
            'description' => $request->description,
            'admin_fee' => $request->admin_fee,
            'weight' => $request->weight,
            'height' => $request->height,
            'length' => $request->length,
            'width' => $request->width,
            'dimension' => $request->dimension,
            'sku' => $request->sku,
            'slug' => Utils::slugify($request->name),
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
        ]);


        if (!$product) {
            return json_encode(['status' => false, 'message'=> 'Insert product failed.']);
        }


        if ($request->option == 'single') {

            $detail = ProductDetail::create([
                'product_id' => $product->id,
                'stock' => $request->stock,
                'price' => $request->price,
                'status' => ($request->status != 'deleted' ? $request->status : 'inactive'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            if (!$detail) {
                return json_encode(['status'=> false, 'message'=> ['Insert product failed.']]);
            }

        } else {
            if ($request->have_variant_2 == "false") {
                foreach ($request->variant_value_1 as $index => $value) {
                    $detail = ProductDetail::create([
                        'product_id' => $product->id,
                        'stock' => $_POST['stock_' . $index],
                        'price' => $_POST['price_' . $index],
                        'variant_key_1' => $request->variant_key_1,
                        'variant_value_1' => $value,
                        'status' => ($request->status != 'deleted' ? $request->status : 'inactive'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            } else {
                $counter = 0;
                foreach ($request->variant_value_1 as $index1 => $value1) {
                    foreach($request->variant_value_2 as $index2 => $value2) {
                        $detail = ProductDetail::create([
                            'product_id' => $product->id,
                            'stock' => $_POST['stock_' . $counter],
                            'price' => $_POST['price_' . $counter],
                            'variant_key_1' => $request->variant_key_1,
                            'variant_value_1' => $value1,
                            'variant_key_2' => $request->variant_key_2,
                            'variant_value_2' => $value2,
                            'status' => $request->status,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_by' => Auth::user()->name,
                            'updated_by' => Auth::user()->name,
                        ]);
                        $counter++;
                    }
                }
            }
        }

        // Check image & video
        if ($request->hasFile('image_1')) {
            $file_name = uniqid();
            $file = $request->image_1;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            array_push($images, $upload);
        }
        if ($request->hasFile('image_2')) {
            $file_name = uniqid();
            $file = $request->image_2;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            array_push($images, $upload);
        }
        if ($request->hasFile('image_3')) {
            $file_name = uniqid();
            $file = $request->image_3;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            array_push($images, $upload);
        }
        if ($request->hasFile('image_4')) {
            $file_name = uniqid();
            $file = $request->image_4;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            array_push($images, $upload);
        }
        if ($request->hasFile('image_5')) {
            $file_name = uniqid();
            $file = $request->image_5;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            array_push($images, $upload);
        }
        if ($request->hasFile('image_6')) {
            $file_name = uniqid();
            $file = $request->image_6;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/vendors/product/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            array_push($images, $upload);
        }
        if ($request->hasFile('video')) {
            $file_name = uniqid();
            $file = $request->video;
            $filename = $file_name .'-product' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/product/';
            $video = Utils::upload_video($destinationPath, $file, $filename);
            if (!$video) {
                return json_encode(['status' => false, 'message' => 'Failed to upload video.']);
            }
        }

        foreach($images as $key => $value) {
            $galleries = ProductGallery::create([
                'product_id' => $product->id,
                'product_detail_id' => null,
                'type' => 1,
                'link' => $value,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            if ($galleries === null) {
                return json_encode(['status'=> false, 'message'=> ['Insert images failed.']]);
            }
        }

        if (isset($video)) {
            $galleries = ProductGallery::create([
                'product_id' => $product->id,
                'product_detail_id' => null,
                'type' => 2,
                'link' => $video,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            if ($galleries === null) {
                return json_encode(['status'=> false, 'message'=> ['Upload video failed.']]);
            }
        }
 
        return json_encode(['status'=> true, 'message'=> 'Success']);
    }

    public function mass_create_view(){
        $categories = Category::where('status','=','active')->get();
        $vendors = Vendor::where('status','=','active')->get();
        $file = ProductUploadFileLog::orderBy('created_at', 'DESC')->get();
        return view('admin.product.upload',compact('categories','vendors','file'));
    }

    public function download(Request $request){
        return Excel::download(new MassProductExcel, 'mass_upload_'.date('y-m-d').'.xlsx');
    }

    public function import_excel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');

        $filename = rand().'_'.$file->getClientOriginalName();

        $storage_path = '/upload/product/mass/';
        $upload = Utils::upload_file($storage_path,$file,$filename);
        $s_path = public_path('products/');
        // $file->move($s_path,$file->getClientOriginalName());   
        
        $name = Auth::user()->name;
        $import = new ProductImport($request->vendor_id,$name);
        $import->onlySheets('Template');
        $excel_import = Excel::import($import, $s_path.$file->getClientOriginalName());
        if($upload){
            // $excel_import = Excel::store($import, $upload, 's3');
            if($excel_import){
                $insert_file = ProductUploadFileLog::create([
                    'path'          => $upload,
                    'created_at'    => Carbon::now(),
                    'created_by'    => Auth::user()->name,
                ]);
            }else{
                return json_encode(['status'=> false, 'message'=> 'Upload failed.']);
            }
            return json_encode(['status'=> true, 'message'=> 'Success']);
        }else{
            return json_encode(['status'=> false, 'message'=> 'Upload video failed.']);
        }
    }

    public function import_excel_(Request $request)
    {

        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

       $file = $request->file('file');

        $filename = rand().'_'.$file->getClientOriginalName();

        $storage_path = public_path('products/');
        $file->move($storage_path,$file->getClientOriginalName());

        $name = Auth::user()->name;
        $import = new ProductImport($request->vendor_id,$name);
        $import->onlySheets('Template');
        $excel_import = Excel::import($import, $storage_path.$file->getClientOriginalName());
        

    }
}
