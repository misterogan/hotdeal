<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $table = 'file_uploads';
    protected $fillable = [
        'id', 'link', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
