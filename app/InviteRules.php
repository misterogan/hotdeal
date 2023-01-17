<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteRules extends Model
{
    protected $table = 'invite_rules';
    protected $fillable = [
        'id','start_date','end_date','count_from','count_after','percentage','status','created_at','created_by','updated_at','updated_by'];
}
