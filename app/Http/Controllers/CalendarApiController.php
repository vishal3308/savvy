<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Google_calendar;
use Illuminate\Support\Facades\Auth;


class CalendarApiController extends Controller
{
    public function calendar_event(Request $request){
        if(is_null($request)){
            
            return response()->json(['status'=>'User Not found']);
        }
        else{
        $user_id=$request->id;
        $Events=Google_calendar::where('user_id', '=',$user_id)->orderBy('id','desc')->get();
        return response()->json($Events);
        }
    }
}
