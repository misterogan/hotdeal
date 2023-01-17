<?php

namespace App\Http\Middleware;

use App\Product;
use App\Visitor;
use Closure;
use Illuminate\Support\Facades\Hash;

class CountVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = Hash::make($request->ip());
        if (Visitor::where('date', today())->where('ip', $ip)->where('slug', $request->route('slug'))->count() < 1) {
            $product = Product::where('slug', $request->route('slug'))->first();
            Visitor::create([
                'date' => today(),
                'ip' => $ip,
                'slug' => $request->route('slug'),
                'vendor_id' => $product->vendor_id,
            ]);
        }

        return $next($request);
    }
}
