<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\SpecialEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SpecialEventController extends Controller
{
    public function index(Request $request){
        return view('admin.special_event.index');
    }

    public function special_event_dt() {
        $special_event = SpecialEvent::whereIn('status', ['active', 'inactive']);
        return DataTables::of($special_event->get())->addIndexColumn()->make(true);
    }

    public function create_view(Request $request){
        return view('admin.special_event.create');
    }

    public function submit(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'event_name'    => 'required',
            'banner'        => 'required',
            'banner_type'   => 'required',
            'about'         => 'required',
            'tnc'           => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'status'        => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $upload = '';
        if ($request->hasFile('banner')) {
            $file_name = uniqid();
            $file = $request->banner;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/special_event/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        }

        $special_event = SpecialEvent::create([
                'event_name'        => $request->event_name,
                'banner_image'       => $upload,
                'banner_type'       => $request->banner_type,
                'about'             => $request->about,
                'tnc'               => $request->tnc,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'status'            => $request->status,
                'slug'              => Utils::slugify($request->event_name),
                'created_by'        => Auth::user()->name,
        ]);

        return json_encode(['status'=> true, 'message'=> 'Special Event has been created']);
    }

    public function edit_view(Request $request) {
        $data = SpecialEvent::where('slug',$request->slug)->first();
        return view('admin.special_event.edit',compact('data'));
    }

    public function update(Request $request){

        $validation = Validator::make($request->all(), [
            'event_name'    => 'required',
            'banner_type'   => 'required',
            'about'         => 'required',
            'tnc'           => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'status'        => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $event = SpecialEvent::where('id',$request->event_id)->first();

        $event->event_name = $request->event_name;

        $upload = '';
        if ($request->hasFile('banner')) {
            $file_name = uniqid();
            $file = $request->banner;
            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
            $destinationPath = '/hotdeal/upload/special_event/';
            $upload = Utils::upload_product_without_watermark($destinationPath, $filename ,$file);
            if (!$upload) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
            $event->banner_image = $upload;
        }

        $event->banner_type = $request->banner_type;
        $event->about = $request->about;
        $event->tnc = $request->tnc;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->status = $request->status;
        $event->slug = Utils::slugify($request->event_name);
        $event->updated_at = date('Y-m-d H:i:s');
        $event->updated_by =  Auth::user()->name;
        $event->save();
        return json_encode(['status'=> true, 'message'=> 'Special Event has been Updated']);
    }
}
