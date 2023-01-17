<?php

namespace App\Http\Resources;

use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantReviewsResource extends JsonResource
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
            'id'=>$this->id,
            'product_id'=>$this->product_id,
            'rating'=>$this->rating,
            'review'=>$this->review,
            'user'=> $this->get_user($this->user_id),
            'product' => $this->get_product($this->product_id),
            'created_at' => $this->get_date($this->date),
            'is_new' => $this->is_new($this->date),
        ];
    }

    function get_user($id) {
        $user = User::select('id', 'name', 'image')->where('id', $id)->first();
        return $user;
    }

    function get_product($id) {
        $product = Product::select('name')
                    ->where('id', $id)->first();

        if($product){
            return $product;
        }
    }

    function get_date($tgl) {
        $data = Carbon::parse($tgl);
        $diff = $data->diffForHumans();
        $date = $data->format("d M Y, H:i");
        $format = $diff.', '.$date;

        return $format;
    }

    function is_new($tgl) {
        $data = Carbon::parse($tgl);
        $now = Carbon::now();
        $diff = Carbon::now()->subDays(5);
        if ($data->between($diff, $now)) {
            return true;
        } else{
            return false;
        }

        return $data;
    }

    
}
