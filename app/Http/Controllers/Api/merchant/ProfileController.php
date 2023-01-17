<?php

namespace App\Http\Controllers\Api\merchant;

use App\Area;
use App\Cities;
use App\Helpers\Utils;
use App\Http\Controllers\Api\Api;
use App\Http\Resources\Merchant\ProfileResource;
use App\Province;
use App\Suburbs;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Api
{
    
    public function get_vendor() {
        //return $this->vendor();
        return $this->successResponse( new ProfileResource($this->vendor())) ;
    }

    public function update_vendor_address(Request $request ){
        $user = $this->vendor();
        $validation = Validator::make($request->all(), [
            'vendor_name' => 'required',
            'city' => 'required',
            'area' => 'required',
            'suburbs' => 'required',
            'address' => 'required',
            'province' => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse(Utils::validation_response_message($validation->errors()->toArray() ,'one') , 201);
        }
        $vendor_address = Vendor::where('user_id' , $user->id)->first();
        $vendor_user = User::where('id', $user->id)->first();
        if(!$vendor_address){
            return $this->errorResponse( 'User tidak ditemukan' , 201);
        }
        $vendor_address->address =  $request->address;
        $vendor_address->name =  $request->vendor_name;
        $vendor_address->city_id =  Cities::select('api_id')->where('id' ,$request->city)->first()->api_id;
        // $vendor_address->zip_code =  $request->zip_code;
        $vendor_address->area_id =  Area::select('api_id')->where('id' ,$request->area)->first()->api_id;
        $vendor_address->province_id =  Province::select('api_id')->where('id' ,$request->province)->first()->api_id;
        $vendor_address->suburb_id =  Suburbs::select('api_id')->where('id' ,$request->suburbs)->first()->api_id;

        $vendor_user->phone = $request->phone;
        $vendor_user->save();

        if(!$vendor_address->save()){
            return $this->errorResponse( 'Gagal mengubah alamat' , 201);
        }
        return $this->successResponse([],'Berhasil mengubah alamat',200);
    }
}
