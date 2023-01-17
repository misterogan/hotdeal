<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Page;
use Carbon\Carbon;
use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    public function index(Request $request){
        return view('admin.page.index');
    }

    public function page_dt() {
        $page = Page::orderBy('id', 'DESC');

        return DataTables::of($page->get())->addIndexColumn()->make(true);
    }

    public function create_view() {
        $id = Auth::user()->id;
        return view('admin.page.create', compact('id'));
    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'mimes:jpg,svg,png,jpeg'
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-page' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/page/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
        } else {
            $upload = null;
        }
        // dd($request->description);

        $page = Page::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' =>  $upload,
            'status' => $request->status,
            'slug' => str_replace(' ', '-', strtolower($request->title)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
        ]);

        if ($page) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $page = Page::where('id', $id)->first();

        return view('admin.page.edit', compact('id', 'page'));
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'mimes:jpg,svg,png,jpeg'
        ]);

        if ($validation->fails()) {
            return json_encode(['status' => false, 'message' => $validation->messages()]);
        }

        $user = Auth::user();
        $data = Page::where('id', $request->id)->first();

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-page' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/page/';
            $upload = Utils::upload_image($destinationPath, $file, $filename);
        } else {
            $upload = $data->image;
        }

        $page = Page::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' =>  $upload,
            'status' => $request->status,
            'slug' => str_replace(' ', '-', strtolower($request->title)),
            'updated_at' => Carbon::now(),
        ]);

        if ($page > 0) {
            return json_encode(['status' => true, 'message' => 'Success']);
        } else {
            return json_encode(['status' => false, 'message' => 'Failed']);
        }
    }
}
