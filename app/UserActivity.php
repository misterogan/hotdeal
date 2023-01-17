<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activity';
    protected $fillable = [
        'user_id', 'activity', 'platform', 'browser', 'ip_address', 'created_at', 'updated_at',  'utm_id', 'utm_source', 'utm_campaign', 'utm_term', 'utm_medium'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
