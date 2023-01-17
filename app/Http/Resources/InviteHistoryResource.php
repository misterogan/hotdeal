<?php

namespace App\Http\Resources;

use App\Helpers\Hotpoint;
use App\OrderDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InviteHistoryResource extends JsonResource
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
            'after'=> $this->after,
            'before'=> $this->before,
            'value' => $this->value,
            'detail' => $this->detail,
            'user' => $this->user($this->order_detail_id),
            'type' => $this->type,
            'status' => $this->type == 'earn' ? "Mendapat Poin" : "Menggunakan Poin",
            'created_at'=> date('Y-m-d' , strtotime($this->created_at)),
            'updated_at'=> $this->updated_at,
            'image' => $this->icon($this->code , $this->type),
            'title' => $this->type($this->code , $this->type)
        ];
    }

    public static function user($detail_id){
        
        //$user = User::select('email' , 'created_at')->where('id' , $user_id)->first();

        $user  = OrderDetail::select('users.email' , 'users.created_at')
                ->join('orders' , 'orders.id' , 'order_details.order_id')
                ->join('users' , 'users.id' , 'orders.user_id')
                ->where('order_details.id' , $detail_id)->first();
                
        if(!$user){
            return ['email' => '' , 'created_at' => '' ,'register' =>''];
        }
        $user->register = date('Y-m-d' , strtotime($user->created_at)); //Carbon::parse($user->created_at)->translatedFormat('j F Y');
        return $user;
        
    }

    public function icon($code , $type){
        if(array_key_exists($code , Hotpoint::CODE)){
            return Hotpoint::CODE[$code]['image'];
        }    
        if($type == 'use'){
            return '/img/assets_using_hotpoint.svg';
        }else if($type == 'earn'){
            return '/img/assets_transaction_hotpoint.svg';
        }
        return '/img/assets_transaction_hotpoint.svg';
    }

    public function type($code , $type){
        if(array_key_exists($code , Hotpoint::CODE)){
            return Hotpoint::CODE[$code]['title'];
        }    
        if($type == 'use'){
            return 'Menggunakan';
        }else if($type == 'earn'){
            return 'Mendapat Point';
        }
        return '';
    }
}
