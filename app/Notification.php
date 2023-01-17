<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'id', 'title', 'body', 'url', 'image', 'status', 'send_to', 'topic', 'send_by', 'browser', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    function details() {
        return $this->hasMany(NotificationDetail::class,'notification_id');
    }
}
