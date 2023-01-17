<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationDetail extends Model
{
    protected $table = 'notification_details';

    protected $fillable = [
        'notification_id', 'user_id', 'is_read', 'created_at', 'updated_at'
    ];

    public function notification() {
        return $this->hasOne(Notification::class, 'id', 'notification_id');
    }
}
