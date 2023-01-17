<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'sender', 'recipient', 'title', 'message', 'url', 'is_read', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function recipient()
    {
        return $this->hasOne(User::class, 'id', 'recipient');
    }
}
