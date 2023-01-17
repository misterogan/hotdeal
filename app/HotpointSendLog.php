<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotpointSendLog extends Model
{
    protected $table = 'hotpoint_send_logs';
    protected $fillable = [
        'amount','email','created_at','updated_at','created_by','description'
    ];
}
