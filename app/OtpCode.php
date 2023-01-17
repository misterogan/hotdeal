<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $table = 'otp_codes';
    protected $fillable = [
        'email','phone_number','code','requested_at','expired_at','created_at', 'updated_at'
    ];
}
