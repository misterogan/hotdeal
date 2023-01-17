<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    protected $table = 'rejeki_nomplok_running_text';

    protected $fillable = [
        'id', 'text', 'status'
    ];
}
