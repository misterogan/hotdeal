<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashsaleLog extends Model
{
    // use HasFactory;
    protected $table = 'flashsale_logs';
    protected $fillable = [
        'id',
        'flashsale_id',
        'before',
        'after',
        'type',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
