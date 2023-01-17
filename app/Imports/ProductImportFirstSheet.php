<?php

namespace App\Imports;

use App\Helpers\Utils;
use App\Jobs\ProductGallery as JobsProductGallery;
use App\Jobs\VariantImageGallery;
use App\ProductGallery;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Product;
use App\ProductDetail;



class ProductImportFirstSheet implements ToCollection,WithStartRow
{

    /**
     * @param Collection $collection
     */
    protected $vendor_id;
    protected $name;

    function __construct($vendor_id,$name) {
        $this->vendor_id = $vendor_id;
        $this->name = $name;
    }
    public function collection(Collection $rows)
    {

        $json = [];

        $detail = [];

        // get details variant
        foreach ($rows as $row){
            $detail[$row[3]][] = [
                'variation_code' => $row[7],
                'variant_key_1' => $row[8],
                'variant_value_1' => $row[9],
                'variant_key_2' => $row[10],
                'variant_value_2' => $row[11],
                'stock' => $row[22],
                'price' => $row[21],
                'status' => strtolower($row[5]),
                'image_variant' => $row[19]
            ];
        }
        
        // get product 
        foreach ($rows as $row) {
           $json[$row[3]] = [
            'brand' => $row[0],
            'name' => $row[1],
            'category_id' => $row[2],
            'sku' => $row[3],
            'description' => str_replace(["\r\n", "\r", "\n"], "<br/>", $row[4]),
            'status' => strtolower($row[5]),
            'have_variant' => strtolower($row[6]) != 'single' ? true : false,
            'admin_fee' => $row[20],
            'price' => $row[21],
            'stock' => $row[22],
            'weight' => $row[23],
            'length' => $row[24],
            'width' => $row[25],
            'height' => $row[26],
            'for_order' => true,
            'dimension' => $row[27],
            'regular' => strtolower($row[28]) != 'aktif' ? false : true,
            'same_day' => strtolower($row[29]) != 'aktif' ? false : true,
            'cod' => strtolower($row[30]) != 'aktif' ? false : true,
            'instant' => strtolower($row[31]) != 'aktif' ? false : true,
            'details' => array_key_exists($row[3] , $detail) ? $detail[$row[3]] : [],
            'gallery' => [
                'main_photo' => $row[12] != '' ? $row[12] : '',
                'image_1' => $row[13] != '' ? $row[13] : '',
                'image_2' => $row[14] != '' ? $row[14] : '',
                'image_3' => $row[15] != '' ? $row[15] : '',
                'image_4' => $row[16] != '' ? $row[16] : '',
                'image_5' => $row[17] != '' ? $row[17] : '',
                'video' => $row[18] != '' ? $row[18] : ''
            ]
           ];
        }

        // create product
        foreach ($json as $data){
            if($data['name'] != null){
                $product = Product::create([
                    'name'              => $data['name'],
                    'vendor_id'         => $this->vendor_id,
                    'category_id'       => $data['category_id'],
                    'brand'             => $data['brand'],
                    'description'       => $data['description'],
                    'short_desc'        => substr(strip_tags($data['description']), 0, 255),
                    'admin_fee'         => $data['admin_fee'],
                    'weight'            => $data['weight'],
                    'height'            => $data['height'],
                    'length'            => $data['length'],
                    'width'             => $data['width'],
                    'rating'            => null,
                    'dimension'         => $data['dimension'],
                    'cod'               => $data['cod'],
                    'for_order'         => $data['for_order'],
                    'sku'               => $data['sku'],
                    'slug'              => Utils::slugify($data['name']),
                    'status'            => strtolower($data['status']),
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'created_by'        => $this->name,
                    'updated_by'        => $this->name,
                ]);

                Utils::log_product($product->id, 'created');
                
                foreach ($data['gallery'] as $key => $gallery){
                    if($gallery != ''){
                        JobsProductGallery::dispatch($product, $gallery, $key, $this->name);
                    }
                }

                if ($data['have_variant'] == true && count($data['details']) > 1) {
                    foreach ($data['details'] as $detail){
                        $product_detail = ProductDetail::create([
                            'product_id'            => $product->id,
                            'stock'                 => $detail['stock'],
                            'price'                 => $detail['price'],
                            'variant_key_1'         => $detail['variant_key_1'],
                            'variant_value_1'       => $detail['variant_value_1'],
                            'variant_key_2'         => $detail['variant_key_2'],
                            'variant_value_2'       => $detail['variant_value_2'],
                            'status'                => 'active',
                            'created_at'            => Carbon::now(),
                            'updated_at'            => Carbon::now(),
                            'created_by'            => $this->name,
                            'updated_by'            => $this->name,
                            'child_sku'          => $detail['variation_code'],
                        ]);
                        
                        if($detail['image_variant'] != ''){
                            VariantImageGallery::dispatch($product_detail, $detail, $this->name, $detail['image_variant']);
                        }
                    }
                } else {
                    $product_detail = ProductDetail::create([
                        'product_id'            => $product->id,
                        'stock'                 => $data['details'][0]['stock'],
                        'price'                 => $data['details'][0]['price'],
                        'variant_key_1'         => $data['details'][0]['variant_key_1'],
                        'variant_value_1'       => $data['details'][0]['variant_value_1'],
                        'variant_key_2'         => $data['details'][0]['variant_key_2'],
                        'variant_value_2'       => $data['details'][0]['variant_value_2'],
                        'status'                => 'active',
                        'created_at'            => Carbon::now(),
                        'updated_at'            => Carbon::now(),
                        'created_by'            => $this->name,
                        'updated_by'            => $this->name,
                    ]);
                }
            }
        }

    }


    public function startRow(): int
    {
        return 4;
    }


}
