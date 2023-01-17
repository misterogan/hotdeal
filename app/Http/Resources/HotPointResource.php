<?php

namespace App\Http\Resources;

use App\Helpers\Hotpoint;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HotPointResource extends JsonResource
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
            'type' => $this->type,
            'status' => $this->type == 'earn' ? "Mendapat Poin" : "Menggunakan Poin",
            'created_at'=> Carbon::parse($this->created_at)->translatedFormat('j F Y H:i:s'),
            'updated_at'=> $this->updated_at,
            'image' => $this->icon($this->code , $this->type),
            'title' => $this->type($this->code , $this->type)
        ];

       
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
