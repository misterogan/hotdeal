<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $category = Category::orderByDesc('created_at')->get(); 

        return view('admin.category.index', compact('category'));
    }

    public function create_view() {
        $parents = Category::where('is_parent', true)->orderBy('category')->get();

        return view('admin.category.create', compact('parents'));
    }

    public function edit_view(Request $request) {
        $category = Category::where('id', $request->id)->first();
        $parents = Category::where('is_parent', true)->orderBy('category')->get();

        return view('admin.category.edit', compact('category', 'parents'));
    }

    public function category_dt() {
        $categories = Category::with('parent')->orderByDesc('created_at');

        return DataTables::of($categories->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request) {
        $validation = Validator::make($request->all(), [
            'category' => 'required',
            'icon' =>'required || mimes:png,svg',
            'image' =>' required || mimes:jpg,bmp,png,jpeg',
        ]);

        if ($request->has('is_parent')) {
            $is_parent = true;
            $parent_id = 0;
        } else {
            $is_parent = false;
            $parent_id = $request->parent_id;
        }

        if ($request->has('show_in_menu')) {
            $show_in_menu = true;
        } else {
            $show_in_menu = false;
        }

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $previous_category = Category::orderByDesc('parent_id')->first();
        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-category' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/categories/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        } else{
            $img = null;
        }
        if ($request->hasFile('icon')) {
            $file_name = uniqid();
            $file = $request->icon;
            $filename = $file_name . '-icon' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/categories/';
            $icn = Utils::upload_image($destinationPath, $file, $filename);
            if (!$icn) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        } else{
            $icn = null;
        }

        $new_category = Category::create([
            'parent_id' => $parent_id,
            'category' => $request->category,
            'is_parent' => $is_parent,
            'image' => $img,
            'icon' => $icn,
            'status' => $request->status,
            'show_in_menu' => $show_in_menu,
            'role' => $request->role,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name
        ]);

        if ($new_category) {
            return json_encode(['status'=> true, 'message'=> "Success"]);
        } else {
            return json_encode(['status'=> false, 'message'=> ["Something went wrong."]]);
        }
    }

    public function update(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'category' => 'required',
            'icon' => 'mimes:png, svg'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $category = Category::where('id', $request->id)->first();

        if ($request->has('is_parent')) {
            $is_parent = true;
            $category->parent_id = 0;
        } else {
            $is_parent = false;
            $category->parent_id = $request->parent_id;
        }

        if ($request->has('show_in_menu')) {
            $show_in_menu = true;
        } else {
            $show_in_menu = false;
        }

        if ($request->hasFile('image')) {
            $file_name = uniqid();
            $file = $request->image;
            $filename = $file_name . '-category' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/categories/';
            $img = Utils::upload_image($destinationPath, $file, $filename);
            if (!$img) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        } else{
            $img = $category->image;
        }

        if ($request->hasFile('icon')) {
            $file_name = uniqid();
            $file = $request->icon;
            $filename = $file_name . '-category' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/categories/';
            $icn = Utils::upload_image($destinationPath, $file, $filename);
            if (!$icn) {
                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
            }
        } else{
            $icn = $category->image;
        }

        if ($category) {
            $category->category = $request->category;
            $category->is_parent = $is_parent;
            $category->status = $request->status;
            $category->image = $img;
            $category->icon = $icn;
            $category->role = $request->role;
            $category->show_in_menu = $show_in_menu;

            if ($category->save()) {
                return json_encode(['status'=> true, 'message'=> "Success"]);
            } else {
                return json_encode(['status'=> false, 'message'=> ["Something went wrong."]]);
            }
        } else {
            return json_encode(['status'=> false, 'message'=> ["Not found"]]);
        }
    }

    public function delete(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($$validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $category = Category::find('id', $request->id);

        if ($category) {
            $category->status = 'inactive';

            if ($category->save()) {
                return json_encode(['status'=> true, 'message'=> "Success"]);
            } else {
                return json_encode(['status'=> false, 'message'=> ["Something went wrong."]]);
            }
        } else {
            return json_encode(['status'=> false, 'message'=> ["Not found"]]);
        }
    }
}
