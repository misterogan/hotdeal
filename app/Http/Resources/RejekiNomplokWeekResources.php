<?php

namespace App\Http\Resources;

use App\OrderDetailProduct;
use App\RejekiNomplokCoupon;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RejekiNomplokWeekResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'week' => $this->week,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'ihsg' => $this->ihsg,
            'total_point' => $this->total_point($this->id),
            'periode' => $this->get_periode($this->start_date, $this->end_date),
            'last_ihsg' => substr($this->ihsg, -1),
            'first_ihsg' => substr($this->ihsg, 0, 3),
        ];
    }

    public function total_point($id) : int {
        $total = RejekiNomplokCoupon::select(DB::raw('sum(value_transaction) as total_bonus'))->where('rejeki_nomplok_week_id' , $id)->first();
        return $total ?  (int)($total->total_bonus) : 0;            
    }
    
    public function get_periode($start, $end) {
        $startDate = Carbon::parse($start)->format('d');
        $endDate = Carbon::parse($end)->format('d');
        $startMonth = Carbon::parse($start)->format('M');
        $endMonth = Carbon::parse($end)->format('M');

        $periode = $startDate.' '.$startMonth.' - '.$endDate.' '.$endMonth.' '.Carbon::parse($end)->format('Y');
        return $periode;
    }

    // public function first($id)
}
