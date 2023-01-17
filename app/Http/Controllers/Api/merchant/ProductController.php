<?php

namespace App\Http\Controllers\Api\merchant;

use App\Category;
use App\Helpers\Utils;
use App\Http\Controllers\Api\Api;
use App\Http\Resources\ProductEditBySlugResource;
use App\Http\Resources\ProductsListResource;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\VendorProductResource;
use App\LogUpdateProduct;
use App\Product;
use App\ProductDetail;
use App\ProductGallery;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ProductController extends Api
{
    /**
     * @OA\Post(
     * path="/api/seller/product/create",
     * summary="ProductList",
     * description="Product Create",
     * operationId="CreateProduct",
     * tags={"Vendor"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="image1",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="image2",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function create(Request $request){
        $user = $this->user();
        $vendor = Vendor::where('user_id',$user->id)->first();
        $request->validate([
            'form' => 'required',
            'image_product' => 'required',
        ]);
        $form_array = json_decode($request->form , true);
        $form = new Request($form_array);

        $form->validate([
            'name' => 'required',
            'description' => 'required',
            'weight' => 'required',
            'dimension' => 'required',
            'sku' => 'required'
        ]);
        $product = [
            'vendor_id' => $vendor->id,
            'name' => $form->name,
            'description' => $form->description,
            'brand' => $form->brand,
            'weight' => $form->weight,
            'sku' => $form->sku,
            'dimension' => $form->dimension,
            'admin_fee' => 0,
            'slug' => $this->generate_slug($form->name),
            'status' => 'inactive',
            'category_id' => $form->category ? $form->category : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $vendor->name,
            'updated_by' => $vendor->name
        ];
        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $quries = DB::getQueryLog();
            $product = [
                'vendor_id' => $vendor->id,
                'name' => $form->name,
                'description' => $form->description,
                'brand' => $form->brand ? $form->brand : 0,
                'weight' => $form->weight,
                'sku' => $form->sku,
                'dimension' => $form->dimension,
                'height' => 0,
                'length' => 0,
                'width' => 0,
                'admin_fee' => 0,
                'slug' => $this->generate_slug($form->name),
                'status' => 'inactive',
                'category_id' => $form->category ? $form->category : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $vendor->name,
                'updated_by' => $vendor->name
            ];
            // SAVE PRODUCT
            $saveProduct = Product::create($product);
            // SAVE MAIN PHOTO
            if(isset($request->main_photo)){
                $file_name = uniqid();
                $file = $request->main_photo;
                $filename = $file_name . '-product' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/hotdeal/upload/vendors/product/';
                $main_picture = Utils::upload_image($destinationPath, $file, $filename);
                if (!$main_picture) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }else{
                    ProductGallery::create(
                        [
                            'product_id' => $saveProduct->id,
                            'product_detail_id' => 0,
                            'type' =>'1',
                            'status' => 'active',
                            'link' => $main_picture,
                            'is_primary' => true,
                            'created_by' => $vendor->name,
                            'updated_by' => $vendor->name,
                            'created_at' => $this->now()
                        ]
                    );
                }
            }
            // SAVE IMAGE TO GALLERY
            if(isset($request->image_product)){
                foreach($request->image_product as $k=> $value){
                    $file = $value;
                    $type = '';
                    if($value != '' && $value != 'undefined'){
                        if(in_array($file->getClientOriginalExtension() , static::IMAGE_MIME)){
                            $folder = '/hotdeal/upload/vendors/product/';
                            $file_name = uniqid();
                            $filename = $file_name .  '.' . Utils::$IMAGE_CONVERT_EXT;
                            $upload = Utils::upload_product_without_watermark($folder,$filename,$file);
                        }elseif(in_array($file->getClientOriginalExtension() , static::VIDEO_MIME)){
                            $folder = '/upload/vendors/product/video/'.$user->id.'/';
                            $file_name = uniqid();
                            $filename = $file_name .  '.' . $file->getClientOriginalExtension();
                            $upload = Utils::upload_video($folder,$filename,$file);
                        }else{
                            return $this->errorResponse('Format file tidak sesuai , gunakan type file webp,jpeg,JPEG,jpg,JPG,png,PNG' ,300);
                        }
                        ProductGallery::create(
                            [
                                'product_id' => $saveProduct->id,
                                'product_detail_id' => 0,
                                'type' =>'1',
                                'status'=> 'active',
                                'link' => $upload,
                                'created_by' => $vendor->name,
                                'updated_by' => $vendor->name,
                                'created_at' => $this->now()
                            ]
                        );
                    }
                }
            }
             // SAVE VIDEO TO GALLERY
            if(isset($request->video)){
                $file_name = uniqid();
                $file = $request->video;
                $filename = $file_name . '-product' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/products/';
                $video = Utils::upload_video($destinationPath, $file, $filename);
                if (!$video) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }else{
                    ProductGallery::create(
                        [
                            'product_id' => $saveProduct->id,
                            'product_detail_id' => 0,
                            'type' =>'2',
                            'status' => 'active',
                            'link' => $video,
                            'created_by' => $vendor->name,
                            'updated_by' => $vendor->name,
                            'created_at' => $this->now()
                        ]
                    );
                }
             }
             // SAVE DETAIL PRODUCT
            $variant_table = array();
            if(isset($request->varianttable)){
                parse_str($request->varianttable, $variant_table);
            }
            if(array_key_exists('variant_1' , $form_array)){

                if(count($form_array['variant_1']['variant']) > 0){

                    foreach($form_array['variant_1']['variant'] as $key => $variant1){
                        if(array_key_exists('variant' , $form_array['variant_2'])){
                            if(count($form_array['variant_2']['variant']) > 0){
                                foreach($form_array['variant_2']['variant'] as $key2 => $variant2){
                                    $data_variant = ProductDetail::create([
                                        'product_id' => $saveProduct->id,
                                        'variant_key_1' => $form_array['variant_1']['label'],
                                        'variant_value_1' => $variant1['option'],
                                        'price' => $variant_table['price_'.$key.'_'.$key2],
                                        'stock' => $variant_table['quantity_'.$key.'_'.$key2],
                                        'child_sku' => $variant_table['sku_'.$key.'_'.$key2],
                                        'variant_key_2' => $form_array['variant_2']['label'],
                                        'variant_value_2' => $variant2['option'] ,
                                        'status' => 'active',
                                        'created_by' => $vendor->name,
                                        'updated_by' => $vendor->name
                                    ]);

                                }
                            }else{
                                $data_variant = ProductDetail::create([
                                    'product_id' => $saveProduct->id,
                                    'variant_key_1' => $form_array['variant_1']['label'],
                                    'variant_value_1' => $variant1['option'],
                                    'price' => $variant_table['price_'.$key],
                                    'stock' => $variant_table['quantity_'.$key],
                                    'child_sku' => $variant_table['sku_'.$key],
                                    'variant_key_2' => '',
                                    'variant_value_2' =>'',
                                    'created_by' => $vendor->name,
                                    'updated_by' => $vendor->name,
                                    'created_at' => Utils::now(),
                                    'updated_by' => 0,
                                    'status' => 'active'
                                ]);
                            }
                        }
                        // SAVE IMAGE VARIANT
                        if(isset($request->image_variant)){
                            if(count($request->image_variant) > 0){
                                if(array_key_exists($key , $request->image_variant)){
                                    $file = $request->image_variant[$key];
                                    if($file != ''){
                                        if(in_array($file->getClientOriginalExtension() , static::IMAGE_MIME)){

                                            $folder = '/hotdeal/upload/vendors/product/';
                                            $file_name = uniqid();
                                            $filename = $file_name .  '.' . Utils::$IMAGE_CONVERT_EXT;
                                            $upload = Utils::upload_product_without_watermark($folder,$filename,$file);
                                        }elseif(in_array($file->getClientOriginalExtension() , static::VIDEO_MIME)){
                                            $folder = '/hotdeal/upload/vendors/product/video';
                                            $file_name = uniqid();
                                            $filename = $file_name .  '.' . $file->getClientOriginalExtension();
                                            $upload = Utils::upload_video($folder,$filename,$file);
                                        }else{
                                            return $this->errorResponse('Format Tidak Sesuai' ,300);
                                        }
                                        ProductGallery::create(
                                            [
                                                'product_id' => $saveProduct->id,
                                                'product_detail_id' => $data_variant->id ? $data_variant->id : '',
                                                'product_variant_image' => $variant1['option'], 
                                                'type' => '1',
                                                'link' => $upload,
                                                'created_by' => $vendor->name,
                                                'updated_by' => $vendor->name,
                                                'created_at' => $this->now(),
                                                'status' => 'active'
                                            ]
                                        );
                                    }

                                }
                            }
                        }

                    }
                }else{
                    ProductDetail::create([
                        'product_id' => $saveProduct->id,
                        'variant_key_1' => '',
                        'variant_value_1' => '',
                        'price' => $form['price'],
                        'stock' => $form['stock'],
                        'variant_key_2' => '',
                        'variant_value_2' =>'',
                        'created_by' => $vendor->name,
                        'updated_by' => $vendor->name,
                        'created_at' => Utils::now(),
                        'updated_by' => 0,
                        'status' => 'active'
                    ]);
                }
            }
            DB::commit();
           // Log::channel('errorlog')->info(['module'=> 'Question', 'function' => 'save', 'class'=>'AskQuestionController' ,'params' =>'' , 'query' => $quries]);
        }catch (Throwable $e) {

           // Log::channel('errorlog')->info(['module'=> 'Question', 'message' => $e->getMessage(), 'query' => $quries]);
            DB::rollback();
            return $this->errorResponse($e->getMessage(),402);
        }
        return $this->successResponse();

    }

    /**
     * @OA\Put(
     * path="/api/seller/product/update",
     * summary="ProductList",
     * description="Product Update",
     * operationId="UpdateProduct",
     * tags={"Vendor"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function update(Request $request){
        // dd($request->all());
        $request->validate([
            'form' => 'required',
            // 'image_product' => 'required',
        ]);
        $form_array = json_decode($request->form , true);
        $form = new Request($form_array);
        $form->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'brand' => 'required',
            'weight' => 'required',
            'dimension' => 'required',
            'slug' => 'required'
        ]);
        $vendor = $this->vendor();
        $product = Product::where('slug' , $form->slug)->where('vendor_id' , $vendor->vendor->id)->first();

        // log before
        $current = Product::where('id', $product->id)->first();
        $current_detail = ProductDetail::where('product_id', $product->id)->where('status', 'active')->get();
        $current_galleries = ProductGallery::where('product_id', $product->id)->where('status', 'active')->get();

        $before['product'] = $current->toArray();
        $before['detail'] = $current_detail->toArray();
        $before['galleries'] = $current_galleries->toArray();

        if($product){
            try {
                DB::beginTransaction();
                $product->name = $form->name;
                $product->description = $form->description;
                $product->brand = $form->brand ? $form->brand : 0;
                $product->weight = $form->weight;
                $product->sku = $form->sku;
                $product->dimension = $form->dimension;
                $product->category_id = $form->category ? $form->category : 0;
                $product->updated_at = Utils::now();
                $product->updated_by = $vendor->name;
                $product->save();
                // dd($request->image_product, $request->image_product_id);
                // MAIN PHOTO
                if(isset($request->main_photo)){
                    if($request->main_photo != 'undefined'){
                        ProductGallery::where('product_id', $product->id)->where('is_primary', true)->delete();
                        ProductGallery::create(
                            [
                                'product_id' => $product->id,
                                'product_detail_id' => 0,
                                'type' =>'1',
                                'status' => 'active',
                                'link' => $request->main_photo,
                                'is_primary' => true,
                                'created_by' => $vendor->name,
                                'updated_by' => $vendor->name,
                                'created_at' => $this->now()
                            ]
                        );
                    }
                }
                // INACTIVE GALLERY
                if(isset($request->image_product_id)){
                    foreach($request->image_product_id as $k=> $value){
                        if($value != null){
                            $up_stat = ProductGallery::where('id', $value)->update([
                                'status' => 'inactive',
                                'updated_by' => $vendor->name,
                            ]);
                        }
                    }
                }
                // SAVE IMAGE TO GALLERY
                if(isset($request->image_product)){
                    foreach($request->image_product as $k=> $value){
                        $file = $value;
                        $type = '';
                        if($value != ''){
                            if(in_array($file->getClientOriginalExtension() , static::IMAGE_MIME)){
                                $folder = '/hotdeal/upload/vendors/product/';
                                $file_name = uniqid();
                                $filename = $file_name .  '.' . Utils::$IMAGE_CONVERT_EXT;
                                $upload = Utils::upload_product_without_watermark($folder,$filename,$file);
                            }elseif(in_array($file->getClientOriginalExtension() , static::VIDEO_MIME)){
                                $folder = '/upload/vendors/product/video/'.$vendor->vendor->id.'/';
                                $file_name = uniqid();
                                $filename = $file_name .  '.' . $file->getClientOriginalExtension();
                                $upload = Utils::upload_video($folder,$filename,$file);
                            }else{
                                return $this->errorResponse('Format file tidak sesuai , gunakan type file webp,jpeg,JPEG,jpg,JPG,png,PNG' ,300);
                            }
                            ProductGallery::create(
                                [
                                    'product_id' => $product->id,
                                    'product_detail_id' => 0,
                                    'type' =>'1',
                                    'status'=> $form->status,
                                    'link' => $upload,
                                    'created_by' => $vendor->name,
                                    'updated_by' => $vendor->name,
                                    'created_at' => $this->now(),
                                ]
                            );
                        }
                    }
                }

                if(count($form->variant_1['variant']) > 0){
                    ProductDetail::where('product_id' , $product->id)->update([
                        'status' => 'inactive'
                    ]);
                    foreach($form->variant_1['variant'] as $item){
                        if(count($form->variant_2['variant']) > 0){
                            foreach($form->variant_2['variant'] as $item2){
                                $details = ProductDetail::where('variant_value_1' ,$item['option'])->where('variant_value_2' , $item2['option'])->first();
                                if($details){
                                    $details->stock = $form->all_variant[$item['option'].'_'.$item2['option']]['quantity'];
                                    $details->price = $form->all_variant[$item['option'].'_'.$item2['option']]['price'];
                                    $details->child_sku = $form->all_variant[$item['option'].'_'.$item2['option']]['sku'];
                                    $details->status = 'active';
                                    $details->updated_by = $vendor->name;
                                    $details->save();
                                }else{
                                    ProductDetail::create([
                                        'product_id' => $product->id,
                                        'variant_key_1' => $form->variant_1['label'],
                                        'variant_value_1' => $item['option'],
                                        'price' => $form->all_variant[$item['option'].'_'.$item2['option']]['price'],
                                        'stock' => $form->all_variant[$item['option'].'_'.$item2['option']]['quantity'],
                                        'child_sku' => $form->all_variant[$item['option'].'_'.$item2['option']]['sku'],
                                        'status' => 'active',
                                        'variant_key_2' => $form->variant_2['label'],
                                        'variant_value_2' =>$item2['option'],
                                        'created_by' => $vendor->name,
                                        'updated_by' => $vendor->name,
                                    ]);
                                }
                            }
                        }else{
                            $details = ProductDetail::where('variant_value_1' ,$item['option'])->where('product_id',$product->id)->first();
                            if($details){
                                $details->stock = $form->all_variant[$item['option'].'_']['quantity'];
                                $details->price = $form->all_variant[$item['option'].'_']['price'];
                                $details->child_sku = $form->all_variant[$item['option'].'_']['sku'];
                                $details->status = 'active';
                                $details->updated_by = $vendor->name;
                                $details->save();
                            }else{
                                ProductDetail::create([
                                    'product_id' => $product->id,
                                    'variant_key_1' => $form->variant_1['label'],
                                    'variant_value_1' => $item['option'],
                                    'price' => $form->all_variant[$item['option'].'_']['price'],
                                    'stock' => $form->all_variant[$item['option'].'_']['quantity'],
                                    'child_sku' => $form->all_variant[$item['option'].'_']['sku'],
                                    'status' => 'active',
                                    'variant_key_2' => '',
                                    'variant_value_2' =>'',
                                    'created_by' => $vendor->name,
                                    'updated_by' => $vendor->name,
                                ]);
                            }

                        }
                    }
                    foreach ($request->image_variant as $key => $value) {
                        $array = explode(',', $value);
                        $gall = ProductGallery::where('product_variant_image', $array[0])->where('product_id', $product->id)->delete();
                        if($array[0] != 'undefined'){
                            ProductGallery::create(
                                [
                                    'product_id' => $product->id,
                                    'product_variant_image' => $array[0], 
                                    'type' => '1',
                                    'link' => $array[1],
                                    'created_at' => $this->now(),
                                    'status' => 'active',
                                    'created_by' => $vendor->name,
                                    'updated_by' => $vendor->name,
                                ]
                            );
                        }
                        
                    }
                }else{
                    ProductDetail::where('product_id' , $product->id)->whereIn('status' , ['active'])->update([
                        'product_id' => $product->id,
                        'variant_key_1' => '',
                        'variant_value_1' => '',
                        'price' => $form->price,
                        'stock' => $form->stock,
                        'child_sku' => $form->sku,
                        'status' => 'active',
                        'variant_key_2' => '',
                        'variant_value_2' =>'',
                        'updated_by' => $vendor->name,
                    ]);
                }
                // log after
                $after_product = Product::where('id', $product->id)->first();
                $after_detail_product = ProductDetail::where('product_id', $product->id)->where('status', 'active')->get();
                $after_galleries = ProductGallery::where('product_id', $product->id)->where('status', 'active')->get();

                $after['product'] = $after_product->toArray();
                $after['detail'] = $after_detail_product->toArray();
                $after['galleries'] = $after_galleries->toArray();
                
                LogUpdateProduct::create([
                    'product_id' => $product->id,
                    'before' => json_encode($before),
                    'after' => json_encode($after),
                    'updated_by' => $vendor->name,
                    'created_at' => $this->now(),
                    'updated_at' => $this->now(),
                ]);

                DB::commit();
                return $this->successResponse([] , 'Berhasil mengubah produk');
            }catch (Throwable $e) {
                print_r($e->getMessage());
                DB::rollback();
            }
        }
        return $this->errorResponse('Gagal mengubah produk' , 301);
    }

    /**
     * @OA\Post(
     * path="/api/seller/product/list",
     * summary="ProductList",
     * description="Product List",
     * operationId="Product List",
     * tags={"Vendor"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function list(Request $request){
        $user = $this->user();
        $vendor = Vendor::select('id')->where('user_id' , $user->id)->first();
        $query = Product::select('name' ,'id' ,'sku' , 'status','slug', 'vendor_id', 'category_id')
                    ->with(['details' => function($query){
                        $query->where('status' , 'active');
                    }])->with(['image' => function($query) {
                        $query->select('id','product_id' , 'link')->where('type', 1);
                      }])
                    ->where('vendor_id' , $vendor->id)->where('status' ,'!=', 'deleted');

        if($request->search != ''){
            $query->where('name' , 'like' , "%{$request->search}%");
        }
        if($request->category != ''){
            $query->where('category_id' , 'like' , "%{$request->category}%");
        }

        $category = Category::where('status', 'active')->select('id','category')->active($vendor->id)->get();

        $query->orderBy('id', 'DESC');
        $products = $query->paginate(10);

        if($products){
            $data['products'] = VendorProductResource::collection($products);
            $data['current_page'] = $products->currentPage();
            $data['total'] = $products->lastPage();
            $data['category'] = $category;
            return $this->successResponse($data);
        }
        else{
            return $this->successResponse([]);
        }


    }

    public function delete(Request $request){
        $user = $this->user();
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'brand' => 'required',
            'weight' => 'required',
            'dimension' => 'required',
        ]);

        DB::beginTransaction();
        DB::enableQueryLog();
        try {
            $quries = DB::getQueryLog();
            $product = [
                'vendor_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
                'brand_id' => $request->brand,
                'weight' => $request->weight,
                'sku' => $request->sku,
                'dimension' => $request->dimension,
                'admin_fee' => '10',
                'category_id' => $request->category ? $request->category : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $user->id,
                'updated_by' => 0
            ];
            $saveProduct = Product::create($product);
            $productDetail = [];
            $data_variant = [
                [
                    'variant_key_1' => 'warna',
                    'variant_value_1' => 'Merah',
                    'price' => 0,
                    'stock' => 0,
                    'varian_key_2' => 'Ukuran',
                    'varian_value_2' => '41'
                ],[
                    'variant_key_1' => 'warna',
                    'variant_value_1' => 'Merah',
                    'price' => 0,
                    'stock' => 0,
                    'varian_key_2' => 'Ukuran',
                    'varian_value_2' => '42'
                ],[
                    'variant_key_1' => 'warna',
                    'variant_value_1' => 'Kuning',
                    'price' => 0,
                    'stock' => 0,
                    'varian_key_2' => 'Ukuran',
                    'varian_value_2' => '41'
                ],[
                    'variant_key_1' => 'warna',
                    'variant_value_1' => 'Kuning',
                    'price' => 0,
                    'stock' => 0,
                    'varian_key_2' => 'Ukuran',
                    'varian_value_2' => '42'
                ],

            ];
            if($request->is_variant == 'true'){
                foreach($data_variant as $key=>$value){
                    $productDetail[] = [
                        'product_id' =>  $saveProduct->id,
                        'variant_key_1' => 'warna',
                        'variant_value_1' => 'Kuning',
                        'price' => 0,
                        'stock' => 0,
                        'variant_key_2' => 'Ukuran',
                        'product_galleries_id' => 1,
                        'variant_value_2' => '42',
                        'created_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_by' => 0,
                    ];
                }
            }
            ProductDetail::insert($productDetail);
            DB::commit();
            Log::channel('errorlog')->info(['module'=> 'Question', 'function' => 'save', 'class'=>'AskQuestionController' ,'params' =>'' , 'query' => $quries]);
        }catch (Throwable $e) {

            Log::channel('errorlog')->info(['module'=> 'Question', 'message' => $e->getMessage(), 'query' => $quries]);
            DB::rollback();
        }

    }

    public function generate_slug($title){
        $title = preg_replace("/[^ \w]+/", "", $title);
        $title = str_replace(' ' , '-' , $title);
        $title = strtolower($title);
        $check_title_from_table = Product::where('slug' , $title)->exists();
        if($check_title_from_table)
            return $title.'-'.Str::random(6) . rand(1000,10000);
        else
            return $title;
    }

    public function product_edit_by_slug(Request $request){
        $user = $this->user();
        $vendor = Vendor::select('id')->where('user_id' , $user->id)->first();

        $product = Product::with('main_photo')->where('slug' , $request->slug)->where('vendor_id' , $vendor->id)->first();
        if(!$product){
            return $this->errorResponse('Gagal membuka produk', 301);
        }
        $data = new ProductEditBySlugResource($product);

        return $this->successResponse($data);

    }

    public function delete_image_by_id(Request $request){
        $request->validate([
            'id' => 'required',
        ]);
        $vendor = $this->vendor();
        $image = ProductGallery::where('id' , $request->id)->with('product')->first();
        if($image){
            if($image->product->vendor_id == $vendor->vendor->id){
                $image->status = 'inactive';
                if($image->save()){
                    return $this->successResponse([] , 'Berhasil menghapus gambar');
                }
            }
        }
        return $this->errorResponse('Gagal menghapus gambar' , 301);
    }

    public function upload_galleries(Request $request){
        $request->validate([
            'slug' => 'required',
            'image' => 'required',
        ]);
        $vendor = $this->vendor();
        $product_id = Product::where('slug' , $request->slug)->first();
        if($product_id->vendor_id == $vendor->vendor->id){
             $file = $request->image;
             if(in_array($file->getClientOriginalExtension() , static::IMAGE_MIME)){
                $folder = '/upload/vendors/product/'.$vendor->vendor->id.'/';
                $file_name = uniqid();
                $filename = $file_name .  '.' . $file->getClientOriginalExtension();
                $upload = Utils::upload_without_watermark($folder,$filename,$file);
            }else{
                return $this->errorResponse('Format file tidak sesuai , gunakan type file jpeg,JPEG,jpg,JPG,png,PNG' ,300);
            }
            return $this->successResponse($upload , 'Berhasil mengunggah gambar.');
        }
        return $this->errorResponse('Gagal mengunggah gambar' , 301);
    }

    public function category_product(){
        $category = Category::where('status', 'active')->get();

        return $this->successResponse($category);

    }

    public function set_variant(Request $request){
        // dd($request->all());
        $option = strtolower($request->option);
        $product = Product::where('slug', $request->slug)->where('status', 'active')->first();
        $gallery = ProductGallery::where('status', 'active')
                    ->where('product_id', $product->id)
                    ->where('product_variant_image', $option)
                    ->update([
                        'status' => 'inactive'
                    ]);

        $galleries = ProductGallery::create(
            [
                'product_id' => $product->id,
                'product_detail_id' => null,
                'type' =>'1',
                'is_primary' => false,
                'status' => 'active',
                'product_variant_image' => $option,
                'link' => '',
                'created_at' => $this->now()
            ]);
        if($galleries){
            return $this->successResponse($galleries);
        } else{
            return $this->errorResponse('Data tidak sesuai', 204);
        }


    }


}
