<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductShortResource;
use App\Http\Resources\UserListTicketResource;
use App\SpecialEvent;
use App\TicketRaffle;
use App\ViewProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class SpecialEventController extends Api
{
    public function bundling_products(){
        $bundling_products = ViewProduct::with(['category' => function($query){
                                $query->where('role' , 'product_bundling');
                            }])->where('product_status' ,'active')->where('product_details_status' , 'active')->limit(4)->get();

        // $products_suggestion = ViewProduct::where('category_id',$products->category_id)->where('product_status' ,'active')->where('product_details_status' , 'active')->limit(4)->get(); 
        
        $data = [
            'product_bundling'=>ProductShortResource::collection($bundling_products),
        ];
        // dd($data);
        return $this->successResponse($data);
    }

    public function ticket_by_transaction(Request $request){
        $user = $this->user();
        if(!$user){
            return $this->successResponse([]);
        }
        $tickets = TicketRaffle::with('order_detail')->with('order_product')->with('order')
                    ->where('user_id', $user->id)
                    ->orderByDesc('id')->get();
        if(!$tickets){
            return $this->successResponse([]);
        }
        $tickets_results = [];

        foreach($tickets as $item){
            $tickets_results[$item->order_id][] = $item;
        }
        $data = [];
        foreach($tickets_results as $new_item){
            $data[] = array(
                'transaction' => $new_item[0]->order,
                'product' => $new_item[0]->order_product,
                'invoice' => $new_item[0]->order_detail,
                'detail' => $new_item
            );
        }
        // dd($this->paginate($data));
        return $this->successResponse($this->paginate($data));
    }

    public function event_detail(Request $request){
        $detail = SpecialEvent::where('status', 'active')->first();
        if(!$detail){
            return $this->errorResponse('', 201);
        }
        $data = [
            'start_date' => Carbon::parse($detail->start_date)->format('d'),
            'end_date' => Carbon::parse($detail->end_date)->format('d'),
            'start_month' => Carbon::parse($detail->start_date)->format('M'),
            'end_month' => Carbon::parse($detail->end_date)->format('M'),
            'end_year' => Carbon::parse($detail->end_date)->format('Y'),
            'banner' => $detail->banner_image,
            'event_name' => $detail->event_name,
            'about' => $detail->about,
            'tnc' => $detail->tnc
        ];
        return $this->successResponse($data);
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
