<?php

namespace App\Http\Controllers\Admin;

use App\Banner;
use App\BannerAdditional;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function index() {
        return view('admin.banner.index');
    }

    public function create_view(Request $request) {
        return view('admin.banner.create');
    }

    public function banner_dt(Request $request) {
        $query = Banner::orderByDesc('created_at');

        return DataTables::of($query->get())->addIndexColumn()->make(true);
    }

    public function banner_filter_dt(Request $request) {
        $banner = Banner::orderByDesc('created_at');

        if ($request->has('search')) {
            if ($request->search !== null) {
                $banner->where('name', 'like', '%' . $request->search . '%');
            }
        }

        if ($request->has('status')) {
            if ($request->status !== null) {
                $banner->where('status', $request->status);
            }
        }

        return DataTables::of($banner->get())->addIndexColumn()->make(true);
    }

    public function edit_view(Request $request) {
        $id = $request->id;

        $data = Banner::where('id', $id)->first();

        return view('admin.banner.edit', compact('id', 'data'));
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'image_upload' => 'required',
            'url' => 'required',
            'status' => 'required',
            'sequence' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();

        if ($request->hasFile('image_upload')) {
            $file_name = uniqid();
            $file = $request->image_upload;
            $filename = $file_name . '-banners' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        }

        // if ($request->hasFile('image_mobile_upload')) {
        //     $file_name = uniqid();
        //     $file = $request->image_mobile_upload;
        //     $filename = $file_name . '-banners' . time() . '.' . $file->getClientOriginalExtension();
        //     $destinationPath = '/upload/banners/';
        //     $img_mobile = Utils::upload_image($destinationPath, $file, $filename);
        //     if (!$img_mobile) {
        //         return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
        //     }
        // }

        // dd($img, $img_mobile);

        if ($request->hasFile('video_upload')) {
            $file_name = uniqid();
            $file = $request->video_upload;
            $filename = $file_name . '-banners' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $vid = Utils::upload_video($destinationPath, $file, $filename);
            if (!$vid) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        } else {
            $vid = null;
        }

        $banner = Banner::create([
            'name' => $request->name,
            'url' => $request->url,
            'img_url' => $img,
            // 'img_url_mobile' => $img,
            'video_url' => $vid,
            'type' => null,
            'sequence' => $request->sequence,
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
            'new_tab' => isset($request->newtab) ? true : false,
        ]);

        if ($banner->first()) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> 'Something went wrong.']);
        }

    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'url' => 'required',
            'status' => 'required',
            'sequence' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        $banner = Banner::where('id', $request->id)->first();

        if ($request->hasFile('image_upload')) {
            $file_name = uniqid();
            $file = $request->image_upload;
            $filename = $file_name .'-banners' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
            }
        } else {
            $img = $banner->img_url;
        }

        // if ($request->hasFile('image_mobile_upload')) {
        //     $file_name = uniqid();
        //     $file = $request->image_mobile_upload;
        //     $filename = $file_name .'-banners' .time(). '.' . $file->getClientOriginalExtension();
        //     $destinationPath = '/upload/banners/';
        //     $img_mobile = Utils::upload_image($destinationPath, $file, $filename);
        //     if (!$img_mobile) {
        //         return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
        //     }
        // } else {
        //     $img_mobile = $banner->img_url_mobile;
        // }

        if ($request->hasFile('video_upload')) {
            $file_name = uniqid();
            $file = $request->video_upload;
            $filename = $file_name .'-banners' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $vid = Utils::upload_video($destinationPath, $file, $filename);
            if (!$vid) {
                return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
            }
        } else {
            $vid = $banner->video_url;
        }

        $banner = Banner::where('id', $request->id)->update([
            'name' => $request->name,
            'url' => $request->url,
            'img_url' => $img,
            // 'img_url_mobile' => $img,
            'video_url' => $vid,
            'sequence' => $request->sequence,
            'status' => $request->status,
            'new_tab' => isset($request->newtab) ? true : false,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        return json_encode(['status'=> true, 'message'=> 'Success']);
    }

    public function get_search(Request $request) {
        echo "test";
    }

    public function delete_image(Request $request){
        // dd($request->data_id);
        $id = $request->data_id;
        Banner::where('id', $id)->update([
            'img_url' => ''
        ]);

    }

    public function popup_view(){
        $banner = BannerAdditional::where('position', 'login')->where('status', 'active')->first();
        return view('admin.banner.popup-login', compact('banner'));
    }

    public function popup_update(Request $request){
        $user = Auth::user();
        $validation = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $banner = BannerAdditional::where('position', 'login')->where('status', 'active')->first();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name .'-banners' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/banners/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status'=> false, 'message'=> ['Failed to upload file.']]);
            }
        } else {
            $img = $banner->img_url;
        }

        if(!$banner){
            BannerAdditional::create([
                'image' => $img,
                'status' => 'active',
                'position' => 'login',
                'created_by' => $user->name,
                'updated_by' => $user->name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else{
            $banner->image = $img;
            $banner->updated_by = $user->name;
            $banner->updated_at = Carbon::now();
            $banner->save();
        }

        return json_encode(['status'=> true, 'message'=> 'Success']);
    }
 }
