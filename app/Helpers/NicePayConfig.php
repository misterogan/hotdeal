<?php
namespace App\Helpers;

use App\Order;
use App\OrderDetail;
use Carbon\Carbon;
use http\Cookie;
use Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class NicePayConfig {

    static $PAYMENT_STATUS_CODE_CREDIT_CARD = ['0' => 'success' , '1' => 'failed' ,'2' => 'Void/Refund' ,'9' => 'Initialization / Reversal'];
    static $PAYMENT_STATUS_CODE_VIRTUAL_ACCOUNT = ['0' => 'paid' , '3' => 'unpaid' ,'4' => 'Expired'];
    static $PAYMENT_STATUS_CODE_E_WALLET_OVO = ['0' => 'paid' , '1' => 'void' ,'8' => 'fail' ,'9'=>'init'];

}
