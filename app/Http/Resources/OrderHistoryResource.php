<?php

namespace App\Http\Resources;

use App\MasterStatusOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
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
            'order_status_id'=>$this->order_status_id,
            'status_name'=>$this->get_status($this->order_status_id_),
        ];
    }

    function get_status($status_id){
        $status = MasterStatusOrder::select('description')->where('id',$status_id)->first();
        return $status->description;
    }
}
