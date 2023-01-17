<?php

namespace App\Http\Middleware;

use Closure;

class FlashSaleAddToCartValid
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

        if($request->pdid == 2277){
            $data = ['code'=>301 , 'message' => 'Produk hanya bisa dibeli pada saat flash sale.' , 'status' => 'error'];
            return response()->json($data);
        }
        return $next($request);
    }
}
