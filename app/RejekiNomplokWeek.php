<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RejekiNomplokWeek extends Model
{
    protected $table = 'rejeki_nomplok_weeks';
    protected $dates = [
        'start_date',
        'end_date'
    ];
    protected $fillable = [
        'week', 'start_date', 'end_date', 'ihsg', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function getStartDateAttribute($date)
    {
        return date('d M Y' , strtotime($date));
        //return Carbon::parse($date)->isoFormat('DD MMM Y');
    }

    public function getEndDateAttribute($date)
    {
        return date('d M Y' , strtotime($date));
        return Carbon::parse($date)->isoFormat('DD MMM Y');
    }

    public function total_point(){
        return $this->hasOne(RejekiNomplokCoupon::class, 'rejeki_nomplok_week_id', 'id')
        ->select('rejeki_nomplok_week_id',DB::raw('sum(value_transaction) as bonus_point'))
        ->groupBy('rejeki_nomplok_week_id');
    }

    public function weeks() {
        return $this->hasMany(RejekiNomplokCoupon::class, 'rejeki_nomplok_week_id', 'id');
    }
}
