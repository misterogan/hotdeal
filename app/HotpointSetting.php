<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotpointSetting extends Model
{
    protected $table = 'hotpoint_settings';

    protected $fillable = [
        'id', 'user_id', 'password', 'tries', 'otp', 'try_again_in','status', 'requested_at', 'expired_at', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
