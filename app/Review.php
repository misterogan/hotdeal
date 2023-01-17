<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'id','product_id', 'user_id', 'rating', 'review','status', 'created_at', 'updated_at', 'created_by', 'updated_by','is_with_video', 'order_details_id'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id')->select('id','name','image');
    }
    public function review_gallery(){
        return $this->hasMany(ReviewGallery::class,'review_id','id')->where('status', 'active')->where('url_source', '!=', '');
    }

    public function image(){
        return $this->hasOne(ReviewGallery::class,'review_id','id')->where('status', 'active')->where('type', 'image');
    }

    public function video(){
        return $this->hasOne(ReviewGallery::class,'review_id','id')->where('status', 'active')->where('type', 'video');
    }

    public function detail() {
        return $this->belongsTo(OrderDetail::class, 'order_details_id', 'id');
    }

    public function get_vendor() {
        return $this->hasManyThrough(
            Product::class,
            Vendor::class,
            'id', 
            'id', 
            'product_id', 
            'id'
        );
    }

    public function vendor_review(){
        return $this->hasOne(VendorReview::class, 'review_id', 'id');
    }

    // public function getCreatedAtAttribute($value){
    //     $date = Carbon::parse($value);
    //     return $date->format('Y-m-d H:i');
    // }
}
