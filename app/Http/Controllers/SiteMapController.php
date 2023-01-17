<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class SiteMapController extends Controller
{
    public function index()
    {
        $productlist= Product::where('status', 'active')->latest()->get();
	 
        return response()->view('sitemap.index', [
            'posts' => $productlist,
        ])->header('Content-Type', 'text/xml');
    }
}
