<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReview extends Model
{
    protected $table = 'vendor_reviews';
    protected $fillable = [
        'id', 'review_id', 'reply', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function review() {
        return $this->belongsTo(Review::class, 'review_id', 'id');
    }
}
