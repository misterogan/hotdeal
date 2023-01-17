<?php

namespace App\Http\Controllers\Api;

use App\Helpers\InviteRules;
use App\Hotpoint;
use App\Http\Requests\StoreInviteRequest;
use App\Http\Requests\UpdateInviteRequest;
use App\Http\Resources\InviteCashbackResource;
use App\Http\Resources\InviteHistoryResource;
use App\OrderDetail;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class InviteController extends Api
{
    public function count_of_child(){
        $user = $this->user();
        if(!$user){
            return 0;
        }
        $total_child = User::where('parent_id' , $user->id)->count();
        return $total_child;
    }

    public function user_child(Request $request){
        $user = $this->user();
        $keyword = $request->keyword;
        
        $users = User::select('name', 'email', 'referal_code')
                ->where('parent_id', $user->id)
                ->where('status', 'active')
                ->where('is_vendor', false);
        
        if(strlen($keyword) > 0){
            $users->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(email)'), 'like', '%' . strtolower($keyword) . '%');
                });
        }
        return $this->successResponse($users->paginate(20));
    }

    public function summary(){
        $user = $this->user();
        $response['child'] = $this->count_of_child();
        $transaction = Hotpoint::select( DB::raw('COUNT(*) as transaction'), DB::raw('SUM(value) as total'))
                        ->groupBy('user_id')
                        ->where('user_id' , $user->id)->where('type' , 'earn')->where('code' , 'EFI001')
                        ->first();
        $cashback = Hotpoint::where('user_id', $user->id)->where('code', 'EFI001')->get();
        $response['url'] = env('APP_URL').'/invite/'.$user->referal_code;
        $response['transaction'] = $transaction ? $transaction : ['transaction' =>0 , 'total' => 0];
        $response['cashback_history'] = InviteCashbackResource::collection($cashback);
        return $this->successResponse($response);
    }

    public function referal_code() {
        $user = $this->user();

        $code = $this->generate_referal_code(7);

        $user = User::where('id', $user->id)->where('status', 'active')->first();
        if(!$user->referal_code) {
            $user->referal_code = $code;
            if($user->save()) {
                $response['referal_code'] = $code;
                return $this->successResponse($response);
            }
        }
        return $this->errorResponse(static::CANT_GENERATE_REFERAL_INVITE_FRIEND , static::ERROR_GENERATE_REFERAL_CODE);
    }

    public function users_reward($invoice_number){
        $total_reward = 0;
        $transaction_by_invoice = OrderDetail::where('invoice_number', $invoice_number)->with('productswithdetailadminfee')->first();
        if(!$transaction_by_invoice){
            return $total_reward;
        }
        foreach($transaction_by_invoice->productswithdetailadminfee as $admin_fee){
            $total_reward += $admin_fee->product_detail_with_product->admin_fee;
        }
        return $total_reward;
    }

    public function generateCode(){
        $user = $this->user();
        if($user->referal_code == ''){
            $user = User::where('id' , $user->id)->first();
            if($user->referal_code == ''){
                $code = InviteRules::generate_referal_code(7);
                $user->referal_code = $code;
                $user->update();
                if(!$user){
                    return $this->errorResponse('Gagal generate referal code' , 500);
                }
            }else{
                return $this->errorResponse('Gagal generate referal codes' , 500); 
            }
            
        }
        return $this->successResponse($user);
    }

    public function transaction_history(Request $request){
        $user = $this->user();
        $response = [
            'total' => 0,
            'current_page' => 0
        ];
        $page = isset($request->page) ?  $request->page : 1;
        
        $data = Hotpoint::where('user_id' , $user->id)->where('type' , 'earn')->where('code' , 'EFI001')
        ->orderBy('id' , 'DESC')
        ->paginate(10);

        if($data){
            $response['current_page'] = $data->currentPage();
            $response['total'] = $data->lastPage();
            $response['data'] = InviteHistoryResource::collection($data);
        }
        return $this->successResponse($response);
    }
}
