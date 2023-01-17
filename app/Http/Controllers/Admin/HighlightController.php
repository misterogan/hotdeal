<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\FlashSale;
use App\FlashSaleDetail;
use App\Helpers\Utils;
use App\HighlightProduct;
use App\HighlightTitle;
use App\Http\Controllers\Controller;
use App\Product;
use App\PromotionVoucher;
use App\PromotionVoucherProduct;
use App\Voucher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

class HighlightController extends Controller
{
    public function index(Request $request) {
        return view('admin.highlight.index');
    }

    public function create_view(Request $request) {
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();

        return view('admin.highlight.create', compact('products', 'categories'));
    }

    public function edit_view(Request $request) {
        $id = $request->id;
        $data = HighlightProduct::where('id', $id)->first();
        $products = Product::with('brand')->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('category')->with('products')->get();

        return view('admin.highlight.edit', compact('id', 'data', 'products', 'categories'));
    }

    public function highlight_dt() {
        $highlight = HighlightProduct::where('highlight_type', '!=', 6)->with('products')->orderBy('highlight_type');

        return DataTables::of($highlight->get())->addIndexColumn()->make(true);
    }

    public function submit(Request $request){
        $validation = Validator::make($request->all(), [
            'product_selection' => 'required',
            'highlight_type' => 'required',
            'status' => 'required',
            'sequence' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();

        if ($request->hasFile('img_square')) {
            $file_name = uniqid();
            $file = $request->img_square;
            $filename = $file_name .'-highlights' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/highlights/';
            $upload_square = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload_square) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else {
            $upload_square = null;
        }

        if ($request->hasFile('img_landscape')) {
            $file_name = uniqid();
            $file = $request->img_landscape;
            $filename = $file_name .'-highlights' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/highlights/';
            $upload_landscape = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload_landscape) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else {
            $upload_landscape = null;
        }

        if ($request->hasFile('img_portrait')) {
            $file_name = uniqid();
            $file = $request->img_portrait;
            $filename = $file_name .'-highlights' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/highlights/';
            $upload_portrait = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload_portrait) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else {
            $upload_portrait = null;
        }
        $newtab = isset($request->newtab) ? true : false;

        $type = $request->highlight_type;
        if($type == "1"){
            $link = $request->deep_link;
        } elseif ($type == "2") {
            if ($request->video) {
                $file_name = uniqid();
                $file = $request->video;
                $filename = $file_name .'-highlights-video' .time(). '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/highlights-video/';
                $link = Utils::upload_video($destinationPath, $file, $filename);
                if (!$link) {
                    return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
                }
            } else {
                $link = null;
            }
        } else {
            $link = null;
        }
        $newtab = $link == null ? false : true; 

        $highlight = HighlightProduct::create([
            'product_id' => $request->product_selection,
            'highlight_type' => $request->highlight_type,
            'sequence' =>  $request->sequence,
            'status' => $request->status,
            'img_square' => $upload_square,
            'img_landscape' => $upload_landscape,
            'img_portrait' => $upload_portrait,
            'deep_link' => $link,
            'new_tab' => $newtab,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        if ($highlight) {
            $status = true;
            $message = "Success";
        } else {
            $status = false;
            $message = "Failed";
        }

        return json_encode(['status'=> $status, 'message'=> $message]);
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'product_selection' => 'required',
            'highlight_type' => 'required',
            'status' => 'required',
            'sequence' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $user = Auth::user();
        $prev = HighlightProduct::where('id', $request->id)->first();

        if ($request->hasFile('img_square')) {
            $file_name = uniqid();
            $file = $request->img_square;
            $filename = $file_name .'-highlights' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/highlights/';
            $upload_square = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload_square) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else if ($prev->img_square != null) {
            $upload_square = $prev->img_square;
        } else {
            $upload_square = null;
        }

        if ($request->hasFile('img_landscape')) {
            $file_name = uniqid();
            $file = $request->img_landscape;
            $filename = $file_name .'-highlights' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/highlights/';
            $upload_landscape = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload_landscape) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else if ($prev->img_landscape != null) {
            $upload_landscape = $prev->img_landscape;
        } else {
            $upload_landscape = null;
        }

        if ($request->hasFile('img_portrait')) {
            $file_name = uniqid();
            $file = $request->img_portrait;
            $filename = $file_name .'-highlights' .time(). '.' . $file->getClientOriginalExtension();
            $destinationPath = '/upload/highlights/';
            $upload_portrait = Utils::upload_image($destinationPath, $file, $filename);
            if (!$upload_portrait) {
                return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
            }
        } else if ($prev->img_portrait != null) {
            $upload_portrait = $prev->img_portrait;
        } else {
            $upload_portrait = null;
        }

        $type = $request->highlight_type;
        if($type == "1"){
            $link = $request->deep_link != null ? $request->deep_link : $prev->deep_link;
        } elseif ($type == "2") {
            if ($request->video) {
                $file_name = uniqid();
                $file = $request->video;
                $filename = $file_name .'-highlights-video' .time(). '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/highlights-video/';
                $link = Utils::upload_video($destinationPath, $file, $filename);
                if (!$link) {
                    return json_encode(['status'=> false, 'message'=> 'Failed to upload file.']);
                }
            } else {
                $link = $prev->deep_link;
            }
        } else {
            $link = null;
        }

        $highlight = HighlightProduct::where('id', $request->id)->update([
            'product_id' => $request->product_selection,
            'highlight_type' => $request->highlight_type,
            'sequence' =>  $request->sequence,
            'img_square' => $upload_square,
            'img_landscape' => $upload_landscape,
            'img_portrait' => $upload_portrait,
            'status' => $request->status,
            'deep_link' => $link,
            'new_tab' => isset($request->newtab) ? true : false,
            'updated_at' => Carbon::now(),
            'updated_by' => $user->name,
        ]);

        if ($highlight) {
            $status = true;
            $message = "Success";
        } else {
            $status = false;
            $message = "Failed";
        }

        return json_encode(['status'=> $status, 'message'=> $message]);
    }


    public function set_title_view(){
        $section_1 = HighlightTitle::where('section', 1)->first();
        $section_2 = HighlightTitle::where('section', 2)->first();
        $section_3 = HighlightTitle::where('section', 3)->first();
        $section_4 = HighlightTitle::where('section', 4)->first();
        if(!$section_1){
            HighlightTitle::create([
                'title' => 'Rekomendasi Untukmu',
                'section' => 1
            ]);
        } 
        if(!$section_2){
            HighlightTitle::create([
                'title' => 'Produk Terlaris',
                'section' => 2
            ]);
        }
        if(!$section_3){
            HighlightTitle::create([
                'title' => 'Spesial Untukmu',
                'section' => 3
            ]);
        }
        if(!$section_4){
            HighlightTitle::create([
                'title' => 'Special Event',
                'section' => 4
            ]);
        }
        
        return view('admin.highlight.set-title', compact('section_1', 'section_2', 'section_3', 'section_4'));
    }

    public function set_title(Request $request){
        $section_1 = HighlightTitle::where('section', 1)->first();
        $section_2 = HighlightTitle::where('section', 2)->first();
        $section_3 = HighlightTitle::where('section', 3)->first();
        $section_4 = HighlightTitle::where('section', 4)->first();

        $section_1->title = !$request->title_1 ? $section_1->title : $request->title_1;
        $section_1->save();
        $section_2->title = !$request->title_2 ? $section_2->title : $request->title_2;
        $section_2->save();
        $section_3->title = !$request->title_3 ? $section_3->title : $request->title_3;
        $section_3->save();
        $section_4->title = !$request->title_4 ? $section_4->title : $request->title_4;
        $section_4->save();
        
        return json_encode(['status'=> true, 'message'=> 'Success']);
    }
}
