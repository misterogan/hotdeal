<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterPartner extends Model
{
    protected $table = 'master_partners';
    protected $fillable = [
        'id', 'partner_name', 'description', 'status', 'image', 'partner_code', 'created_by', 'updated_by', 'created_at', 'updated_at','token'
    ];
}

