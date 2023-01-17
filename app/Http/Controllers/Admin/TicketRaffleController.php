<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TicketRaffle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TicketRaffleController extends Controller
{
    public function list($id){
      
        return view('admin.events.ticket-raffle.index');
    }

    public function data(Request $request){
        
        $data = TicketRaffle::with('user')->with('special_event')->where('special_event_id', $request->special_event_id)->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }
}
