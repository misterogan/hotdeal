<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUploadFileLog extends Model
{
    protected $table = 'product_upload_file_log';
    protected $fillable = [
        'id','path','created_at', 'created_by'
    ];
}
