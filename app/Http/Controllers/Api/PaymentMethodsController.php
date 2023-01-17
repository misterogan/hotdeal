<?php

namespace App\Http\Controllers\Api;

use App\PaymentMethod;

class PaymentMethodsController extends Api
{
    public function list(){
        $data = PaymentMethod::where('status','active')->get();
        $result = [];
        if($data){
            foreach($data as $key=>$val){
                $result[$val->channel][] = $val;
            }
            foreach($result as $key=>$val){
                $results[] = [
                    "label" => $key,
                    'items' => $val,
                    'test' => '123'
                ];
            }
        }
        return $this->successResponse($results);
    }
}
