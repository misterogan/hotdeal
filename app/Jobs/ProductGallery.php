<?php

namespace App\Jobs;

use App\Helpers\Utils;
use App\ProductGallery as AppProductGallery;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductGallery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $product;
    protected $gallery; 
    protected $key;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $gallery, $key, $name)
    {
        $this->product = $product;
        $this->gallery = $gallery;
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // if(config('app.env') != 'production'){
        //     return ;
        // }
        $file = $this->gallery;
        // Check Image from Google Drive
        $content = Utils::check_drive($file);

        $file_name = uniqid();
        $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
        $path = '/hotdeal/upload/vendors/product/';

        $upload = Utils::upload_without_watermark_to_webp($path,$filename,file_get_contents($content));
        
        $product_gallery = AppProductGallery::create([
            'product_id'          =>$this->product->id,
            'type'                =>$this->key != 'video' ? 1 : 2,
            'link'                =>$upload,
            'status'              =>'active',
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
            'created_by'            => $this->name,
            'updated_by'            => $this->name,
        ]);
    }
}
