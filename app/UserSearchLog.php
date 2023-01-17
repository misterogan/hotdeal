<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSearchLog extends Model
{
    protected $table = 'user_search_logs';
    protected $fillable = [
        'user_id', 'keyword', 'created_at', 'updated_at'
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
