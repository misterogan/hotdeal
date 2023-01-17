<?php

namespace App\Http\Controllers\Admin;

use App\Coupon;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\MasterPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index(){
        return view('admin.partner.index');
    }

    public function get_dt(Request $request){
        $partners = MasterPartner::orderByDesc('created_at');
        return DataTables::of($partners->get())->addIndexColumn()->make(true);
    }

    public function get_code(Request $request){

        $partner = MasterPartner::where('id',$request->id_partner)->first();
        return json_encode(['status'=> true, 'code'=> $partner->partner_code]);
    }

    public function create_view(Request $request){
        return view('admin.partner.create');
    }
    public function submit(Request $request){
        $user = Auth::user();
        $validation = Validator::make($request->all(), [
            'partner_name'          => 'required',
            'partner_code'          => 'required',
            'status'                => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }
        $token = Str::random(60);
        $upload = '';

        if ($request->hasFile('partner_image')) {
            $file_name = uniqid();
            $file = $request->partner_image;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/partners/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }

        }

        if($request->footer == "true"){
            $footer = true;
        } else{
            $footer = false;
        };
        $partner = MasterPartner::create([
           'partner_name'       =>$request->partner_name,
           'partner_code'       =>$request->partner_code,
           'description'        =>$request->description,
           'image'              =>$upload,
           'show_if_footer'     =>$footer,
           'status'             =>$request->status,
            'created_by'        => $user->name,
            'updated_by'        => $user->name,
            'token'             => hash('sha256', $token),
        ]);

        return json_encode(['status'=> true, 'message'=> 'Success']);
    }

    public function edit_view(Request $request){
        $partner = MasterPartner::where('id',$request->id)->first();
        return view('admin.partner.edit',compact('partner'));
    }

    public function edit(Request $request){
        $user = Auth::user();

        $partner = MasterPartner::where('id',$request->partner_id)->first();
        $upload = $partner->image;

        if ($request->hasFile('partner_image')) {
            $file_name = uniqid();
            $file = $request->partner_image;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/partners/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        }
        if($request->footer == "true"){
            $footer = true;
        } else{
            $footer = false;
        };

        MasterPartner::where('id', $request->partner_id)->update([
            'partner_name'       =>$request->partner_name,
            'partner_code'       =>$request->partner_code,
            'description'        =>$request->description,
            'status'             =>$request->status,
            'show_in_footer'     =>$footer,
            'image'              =>$upload,
            'updated_by'        => $user->name,
        ]);
        return json_encode(['status'=> true, 'message'=> 'Success']);
    }
}
