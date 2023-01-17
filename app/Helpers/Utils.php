<?php
namespace App\Helpers;

use App\Dau;
use App\LogUpdateProduct;
use App\Notification;
use App\NotificationDetail;
use App\Nru;
use App\Order;
use App\Page;
use App\OrderDetail;
use App\Product;
use App\ProductDetail;
use App\ProductGallery;
use App\RejekiNomplokWeek;
use Carbon\Carbon;
use http\Cookie;
use Illuminate\Support\Facades\Cache;
use Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Utils {
    static $IMAGE_CONVERT_EXT = 'webp';

    public static function upload_galery($path,$filename,$file)
    {
        $file->move($path, $filename);
        return $filename;
    }

    public static function upload_image($storage_path, $file,$filename){
        $bucket = Storage::disk('s3')->put($storage_path.'' . $filename, file_get_contents($file));
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$storage_path.$filename;
        }else{
            return false;
        }
    }

    public static function upload_file($storage_path, $file,$filename){
        $bucket = Storage::disk('s3')->put($storage_path.'' . $filename, file_get_contents($file));
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$storage_path.$filename;
        }else{
            return false;
        }
    }

    public static function phoneformatE164 ($phone){
        return '+62'.substr($phone , 1);
    }

    public static function upload_video($storage_path, $file, $filename , $ext = null)
    {

        if(!$ext){
            $bucket = Storage::disk('s3')->put($storage_path.'/' . $filename, file_get_contents($file));
        }else{

            $bucket = Storage::disk('s3')->put($storage_path.'/' . $filename, file_get_contents($file) , [
                'Content-Type' => 'audio/mpeg',
                'visibility' => 'public',
                'mimetype' => 'video/'.$ext
            ]);
        }
        if ($bucket) {
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$storage_path.$filename;
        }else{
            return false;
        }
    }

    public static function upload_with_watermark($folder,$filename,$file){
        ini_set('memory_limit', '800M');
        $img = Image::make($file);
        /** maksimal height and widht image [Request by hotdeal operation] */
        $width = 1000;
        $height = 1000;
        $img->height() > $img->width() ? $width=null : $height=null;
        /** Resize gambar berdasarkan aspek rasio gambar */
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        /** setting tinggi dan lebar watermark*/
        $w_watermark = ((1/100) * $img->width());
        $h_watermark = ((1/100) * $img->height());
        $img->insert(public_path('images/hotdeal-watermark.png'), 'bottom-right', (int)($w_watermark), (int)($h_watermark));
        /** Upload data ke s3 */
        $bucket = Storage::disk('s3')->put($folder .'/'. $filename, $img->stream());
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$folder.$filename;
        }else{
            return false;
        }
    }

    public static function upload_without_watermark($folder,$filename,$file){
        ini_set('memory_limit', '800M');
        $img = Image::make($file);
        /** maksimal height and widht image [Request by hotdeal operation] */
        $width = 1000;
        $height = 1000;
        $img->height() > $img->width() ? $width=null : $height=null;
        /** Resize gambar berdasarkan aspek rasio gambar */
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate();
        $bucket = Storage::disk('s3')->put($folder .'/'. $filename, $img->stream());
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$folder.$filename;
        }else{
            return false;
        }
    }

    public static function upload_base64_image($folder, $filename, $file){
        $image = str_replace(' ', '+', $file);
        $imageName = md5(rand(11111, 99999)) . '_' . time() . '.jpg';
        $bucket = Storage::disk('s3')->put($folder .'/'. $filename, base64_decode($file));
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$folder.$filename;
        }else{
            return false;
        }
    }

    public static function upload_product_without_watermark($folder,$filename,$file){
        ini_set('memory_limit', '800M');
        $img = Image::make($file);
        /** maksimal height and widht image [Request by hotdeal operation] */
        $width = 600;
        $height = 600;
        $img->height() > $img->width() ? $width=null : $height=null;
        /** Resize gambar berdasarkan aspek rasio gambar */
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate();
        $bucket = Storage::disk('s3')->put($folder .'/'. $filename, $img->stream());
         // thumbnail 300 x 300
        self::upload_thumbnail(300 , 300 , $folder,$filename,$file);
        self::upload_thumbnail(100 , 100 , $folder,$filename,$file);
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$folder.$filename;
        }else{
            return false;
        }
    }

    public static function upload_without_watermark_to_webp($folder,$filename,$file){
        ini_set('memory_limit', '800M');
        $img = Image::make($file);
        /** maksimal height and widht image [Request by hotdeal operation] */
        $width = 1000;
        $height = 1000;
        $img->height() > $img->width() ? $width=null : $height=null;
        /** Resize gambar berdasarkan aspek rasio gambar */
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate();
        $bucket = Storage::disk('s3')->put($folder .'/'. $filename, $img->stream());
         // thumbnail 300 x 300
        self::upload_thumbnail(300 , 300 , $folder,$filename,$file);
        self::upload_thumbnail(100 , 100 , $folder,$filename,$file);
        if($bucket){
            return 'https://s3.'. env('AWS_DEFAULT_REGION') .'.'. env('AWS_URI').'/'.env('AWS_BUCKET').$folder.$filename;
        }else{
            return false;
        }
    }


    static function upload_thumbnail($height , $width, $folder,$filename,$file){
        ini_set('memory_limit', '800M');
        $img = Image::make($file);
        $img->height() > $img->width() ? $width=null : $height=null;
        /** Resize gambar berdasarkan aspek rasio gambar */
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->orientate();
        Storage::disk('s3')->put($folder .$width.'-square/'. $filename, $img->stream());
    }

    public static function currency_convert($amount){
        $result =  'Rp '.number_format($amount,0, ',' , '.');
        return $result;
    }

    public static function set_memcache($keycache,$keyword)
    {
        $seconds = 3600;
        $response = \Cache::put($keycache, $keyword, $seconds);
        return $response;
    }

    public static function number_for_badge($num){
        if($num > 9){
            return '9+';
        }else{
            return $num;
        }
    }

    public static function transaction_number($user_id){
        mt_srand((double)microtime()*10000);
        $charid = md5(uniqid(rand(), true));
        $c = unpack("C*",$charid);
        $c = implode("",$c);
        return "TRANX".substr($c,0,8);
    }
    public static function invoice_number(){
        $year =  date('Y');
        $month_date =  date('md');
        $year = substr( $year, -2);
        $order_detail = OrderDetail::whereDate('created_at', Carbon::today())->count();
        if($order_detail > 0){
            $number = str_pad(intval('000'.$order_detail) + 1, strlen('000'.$order_detail), '0', STR_PAD_LEFT);
        }else{
            $number = '0001';
        }
        return 'INV/'.$year.$month_date.'/HDID/'.$number;
    }

    public static function now(){
       return date('Y-m-d H:i:s');
    }
    public static function slugify($text, $divider = '-')
    {
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $divider);
        $text = preg_replace('~-+~', $divider, $text);
        $text = strtolower($text);
        if (empty($text)) {
            return false;
        }
        return $text;
    }

    public static function unslugify($text, $divider = '/') {
        $text = str_replace('-', $divider, $text);

        $text = strtoupper($text);
        if (empty($text)) {
            return false;
        }
        return $text;
    }

    public static function change_date($date){
        return date("d/m/Y", strtotime($date));
    }

    public static function date_to_picker($date) {
        return date("m/d/Y H:i:s", strtotime($date));
    }

    public static function generate_code() {
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

    public static function random(){
        return rand(1000,10000);
    }

    public static function generate_random_length($length) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }



    public static function cdn_version(){
        return 254;
    }

    public static function default_meta()
    {
        $meta = [
            'title' => 'Hotdeal.id | No #1 Video Commerce',
            'description' => 'Hotdeal.id adalah platform e-commerce dari PT. Cashtree For Indonesia yang merupakan portal video commerce pertama di Indonesia. Hotdeal.id mengangkat konsep HOT dari segi kualitas produk dan DEAL dari segi harga serta dikemas dalam sebuah video yang menarik. Sebagai sebuah platform e-commerce baru, Hotdeal.id dikembangkan untuk selalu mengedepankan kemudahan konsumen saat berbelanja dengan memperjelas detail produk dalam bentuk video. Hal itu dibuat untuk mengurangi risiko konsumen mengalami perbedaan produk yang dipesan dengan produk yang sampai ke tangan konsumen.
Untuk informasi lebih lengkap hubungi kami di info@hotdeal.id.',
            'image'=>'https://hotdeal.id/img/logo.svg'
        ];

        return $meta;
    }

    public static function get_product_detail_meta($slug){
        $product = Cache::rememberForever("meta_" .$slug, function () use ($slug){
            return \App\Product::where('slug','=', $slug)->with('image')->first();
        });
        if($product){
            $meta = [
                'title' => $product->name . ' - Hotdeal.id',
                'description' => substr(strip_tags($product->short_desc), 0, 255),
                'image'=> $product->image ? $product->image->link : ''
            ];
        }else{
            $meta = [
                'title' => 'Hotdeal.id | No #1 Video Commerce',
                'description' => 'Hotdeal.id adalah platform e-commerce dari PT. Cashtree For Indonesia yang merupakan portal video commerce pertama di Indonesia. Hotdeal.id mengangkat konsep HOT dari segi kualitas produk dan DEAL dari segi harga serta dikemas dalam sebuah video yang menarik. Sebagai sebuah platform e-commerce baru, Hotdeal.id dikembangkan untuk selalu mengedepankan kemudahan konsumen saat berbelanja dengan memperjelas detail produk dalam bentuk video. Hal itu dibuat untuk mengurangi risiko konsumen mengalami perbedaan produk yang dipesan dengan produk yang sampai ke tangan konsumen.
    Untuk informasi lebih lengkap hubungi kami di info@hotdeal.id.',
                'image'=>'https://hotdeal.id/img/logo.svg'
            ];

        }


        return $meta;
    }

    public static function get_news_detail_meta($slug){
        $page = \App\Page::where('slug','=', $slug)->where('status', 'active')->first();
        $meta = [
            'title' => $page->title . ' - Hotdeal.id',
            'description' => $page->description,
            'image'=> $page->image
        ];

        return $meta;
    }

    public static function get_merchant_meta($id){
        $vendor = \App\Vendor::where('id','=', $id)->first();
        $meta = [
            'title' => $vendor->name . ' - Hotdeal.id',
            'description' => $vendor->name,
            'image'=> $vendor->image
        ];

        return $meta;
    }

    public static function validation_response_message($data , $status = 'one'){
        $message = '';
        if(is_array($data)){
            foreach($data as $key => $val){
                if($status == 'one'){
                    return $val[0];
                }else{
                    $message .= $message.$val[0].' ,';
                }
            }
        }
        return substr($message, 0, -1);
    }

    public static function randomHex($qty) {
        $data_colour = [];
        for ( $j = 0; $j < $qty; $j++ ) {
            $chars = 'ABCDEF0123456789';
            $color = '#';
            for ( $i = 0; $i < 6; $i++ ) {
                $color .= $chars[rand(0, strlen($chars) - 1)];
            }
            $data_colour[] = $color;
        }
        return $data_colour;
     }

    public static function admin_notification(){
        $notif = Notification::select('notification_details.user_id as user', 'title', 'body', 'url')
                ->leftJoin('notification_details', 'notification_details.notification_id', '=', 'notifications.id')
                ->where('notification_details.user_id', '0')
                ->orderBy('notifications.id', 'desc')
                ->take(6)
                ->groupBy('notification_details.id', 'notifications.title', 'notifications.body', 'notifications.url', 'notifications.id')
                ->get();
        return $notif;
    }

    public static function make_human_number($number){
        if($number > 1000 ){
            return  round( $number/1000, 1) . "rb+";
        }
        return $number;
    }

    public static function log_product($product_id, $type){
        //  Log
        $log_product = Product::where('id', $product_id)->first();
        $log_detail_product = ProductDetail::where('product_id', $product_id)->where('status', 'active')->get();
        $log_galleries = ProductGallery::where('product_id', $product_id)->where('status', 'active')->get();

        $log['product'] = $log_product->toArray();
        $log['detail'] = $log_detail_product->toArray();
        $log['galleries'] = $log_galleries->toArray();

        LogUpdateProduct::create([
            'product_id' => $product_id,
            'before' => json_encode($log),
            'after' => json_encode($log),
            'type' => $type,
            'updated_by' => Auth::user()->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public static function add_dau($id){
        if(isset($_COOKIE['has_login'])){
            $today = Carbon::now();
            $dau = Dau::where('user_id', $id)->orderByDesc('id')->whereDate('created_at',$today)->first();
            if(!$dau){
                Dau::create([
                    'user_id' => $id,
                    'created_at' => $today,
                    'updated_at' => $today
                ]);
            }
        }
    }

    public static function add_nru($user_id){
        $nru = Nru::where('user_id',$user_id)->first();
        if(!$nru){
            Nru::create([
                'user_id'=> $user_id,
                'created_at'=>date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function check_drive($file){
        $source = substr($file, 8, 5);
        if($source == 'drive'){
            $uri_path = $file;
            $uri_segments = explode('/', $uri_path);
            $id_drive = $uri_segments[5];
            $content = 'https://drive.google.com/u/0/uc?id='.$id_drive.'&export=download';
        } else {
            $content = $file;
        }
        return $content;
    }

    public static function rejeki_nomplok_week(){
        $week = substr(RejekiNomplokWeek::where('status', 'active')->orderByDesc('id')->pluck('week')->first(), -2) + 1;
        return (int)(date('Y').$week);
    }
}
