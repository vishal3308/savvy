<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Google_calendar;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Meeting_transcript;



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
    public function User_details(){
        $user_id=Auth::User()->id;
        $user=['name'=>"Vishal",'user_id'=>$user_id];
        return response()->json($user);
    }

    public function transcription(Request $request){
        $user_id=$request->id;
        $meeting_id=$request->meeting_id;
        $transcript_text=$request->transcript_text;
        $meeting_transcript=Meeting_transcript::where('meeting_id', '=',$meeting_id)->first();
        if($meeting_transcript){
            $meeting_transcript->transcript_text=$transcript_text;
            $meeting_transcript->update();
        }
        else{
            $meeting_transcript=new Meeting_transcript();
            $meeting_transcript->meeting_id=$meeting_id;
            $meeting_transcript->user_id=$user_id;
            $meeting_transcript->transcript_text=$transcript_text;
            $meeting_transcript->save();
        }
       
        return response()->json(["status"=>$meeting_transcript,"Request"=>$transcript_text]);
        // return response()->json(['output'=>$request->all()]);
    }
}
