<?php

namespace App\Http\Controllers\Admin;

use App\FileUpload;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FileUploadController extends Controller
{
    public function upload_index(){
        return view('admin.file-upload.index');
    }

    public function file_dt(){
        $files = FileUpload::get();
        return DataTables::of($files)->addIndexColumn()->make(true);
    }

    public function create_view(){
        return view('admin.file-upload.create');
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'file_upload' => 'max:100000',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        if ($request->hasFile('file_upload')) {
            $file_name = uniqid();
            $file = $request->file_upload;
            $filename = $file_name . '-files' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/files/';
            $img = Utils::upload_file($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        }

        $user = Auth::user();
        
        $upload = FileUpload::create([
            'link' => $img,
            'created_at' =>Carbon::now(),
            'updated_at' =>Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name
        ]);

        if($upload){
            return json_encode(['status' => true, 'message' => 'Success to upload file.']);
        }

        return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
    }
}
