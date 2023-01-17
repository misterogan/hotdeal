<?php

namespace App\Http\Resources;

use App\Cart;
use App\Cities;
use App\Helpers\Utils;
use App\Wishlist;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CartResource extends JsonResource
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
            'product_details_id'=>$this->product_details_id,
            'quantity'=>$this->quantity,
            'created_at'=>$this->created_at,
            'total' => Utils::currency_convert($this->quantity * $this->view_product->face_price),
            'updated_at'=>$this->updated_at,
            'is_checkout' => $this->in_checkout == 1 ? true : false,
            'product_detail'=> new ViewProductResource($this->view_product),
            'in_wish_list' => $this->check_wishlist($this->product_id),
            'notes' => $this->notes
        ];
    }

    private function check_wishlist($id){
        $user = Auth::user();
        $wishlist = Wishlist::where('product_id', $id)->where('status', 'active')->where('user_id', $user->id)->first();
        if($wishlist){
            return true;
        }

        return false;
    }
}
