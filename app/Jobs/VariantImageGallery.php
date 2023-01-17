<?php

namespace App\Jobs;

use App\Helpers\Utils;
use App\ProductGallery;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VariantImageGallery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $product_detail;
    protected $detail;
    protected $name;
    protected $gallery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product_detail, $detail, $name, $gallery)
    {
        $this->product_detail = $product_detail;
        $this->detail = $detail;
        $this->name = $name;
        $this->gallery = $gallery;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->gallery;
        // Check Image from Google Drive
        $content = Utils::check_drive($file);

        $file_name = uniqid();
        $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
        $path = '/hotdeal/upload/vendors/product/';

        $upload = Utils::upload_without_watermark_to_webp($path,$filename,file_get_contents($content));
        
        ProductGallery::create([
            'product_id'          =>$this->product_detail->product_id,
            'product_detail_id'   =>$this->product_detail->id,
            'type'                =>'1',
            'link'                =>$upload,
            'status'              =>'active',
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
            'created_by'            => $this->name,
            'updated_by'            => $this->name,
            'product_variant_image' => $this->detail['variant_value_1'],
        ]);
    }
}
