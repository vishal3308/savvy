<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Meeting_transcript;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function view(){
        $user= User::with('get_company')->get();
        return response()->json($user);
    }
    public function set_meeting(Request $request){
        // echo"<pre>";
        // print_r($request['meeting_owner']);
        // echo"<pre>";
        $meeting=Meeting::where("external_meeting_id", '=',$request['callId'])->first();
        if(!$meeting){
            $meeting=new Meeting();
            $meeting->meeting_owner_id=$request['meeting_owner'];
            $meeting->external_meeting_id=$request['callId'];
            $meeting->external_meeting_name=$request['name'];
            $meeting->save();
        }
        
        return response()->json(['status'=>$meeting->id]);
    }

    public function transcription(Request $request){
        $user_id=$request->id;
        $meeting=Meeting::where("external_meeting_id", '=',$request->transcript['meeting_id'])->first();
        if(!$meeting){
            $meeting=new Meeting();
            $meeting->meeting_owner_id=$user_id;
            $meeting->external_meeting_id=$request->transcript['meeting_id'];
            $meeting->external_meeting_name=$request->transcript['meeting_name'];
            $meeting->save();
        }

        $transcript_text=$request->transcript['text'];
        $speaker_name=$request->transcript['speaker']['name'];
        $start=date("Y-m-d h:i:s",strtotime($request->transcript['duration']['start']));
        $end=date("Y-m-d h:i:s",strtotime($request->transcript['duration']['end']));
        $meeting_transcript=Meeting_transcript::where('meeting_id', '=',$meeting->id)->orderBy("id","desc")->first();
        if($meeting_transcript){
            $speaker_start=date("Y-m-d h:i:s",strtotime($meeting_transcript->created_at));
            if($speaker_start==$start & $speaker_name==$meeting_transcript->speaker_name){
                $meeting_transcript->transcript_text=$transcript_text;
                $meeting_transcript->updated_at=$end;
                $meeting_transcript->update();
            }
            else{
                $meeting_transcript=new Meeting_transcript();
                $meeting_transcript->meeting_id=$meeting->id;
                $meeting_transcript->user_id=$user_id;
                $meeting_transcript->transcript_text=$transcript_text;
                $meeting_transcript->speaker_name=$speaker_name;
                $meeting_transcript->created_at=$start;
                $meeting_transcript->updated_at=$end;
                $meeting_transcript->save();
            }
           
        }
        else{
            $meeting_transcript=new Meeting_transcript();
            $meeting_transcript->meeting_id=$meeting->id;
            $meeting_transcript->user_id=$user_id;
            $meeting_transcript->transcript_text=$transcript_text;
            $meeting_transcript->speaker_name=$speaker_name;
            $meeting_transcript->created_at=$start;
            $meeting_transcript->updated_at=$end;
            $meeting_transcript->save();
        }
       
        return response()->json(["dbdate"=>$meeting_transcript->created_at,"start"=>$start]);
        // return response()->json(['output'=>$request->all()]);
    }

    public function transcript_respond(Request $request){
        $meeting_id=$request->meeting_id;
        $meeting_transcript=Meeting_transcript::where('meeting_id', '=',$meeting_id)->first();
        if($meeting_transcript){
            return response()->json(['Response'=>$meeting_transcript->transcript_text]);
        }
        else{
            return response()->json(['Response'=>'No Result']);
        }
    }

    public function find_user(Request $request){
        $user=User::with(['get_company'])->find($request['meeting_owner']);
        // $data=compact('user');
        // return view('apiout')->with($data);
        $meeting_id=$request['callId'];
        $meeting=DB::table('meetings')->where('external_meeting_id',$meeting_id)->first();
        
        echo"<pre>";
        // print_r($meeting);
        // print_r($meeting->external_meeting_id);
        $data=[
            'owner' => $user->id,
            'callId' => $meeting->external_meeting_id,
            'transcriptLines' => Array
                (
                )
        ,
            'duration' => Array
                (
                    'start' => $meeting->created_at ,
                    'end' => '' ,
                    '_id' => $meeting->id
                )
        ,
            'ended' => false,
            'name' => $meeting->external_meeting_name,
            'participants' => Array
                (
                )
        ,
            'language' => 'ENG',
            'summaryEnabled' => 1,
            'gDocEnabled' => false,
            'access' => Array
                (
                    'linkAccess' => 'RESTRICTED',
                    'accessCode' => 'd6e4e297-a4a7-45e4-8fd7-72cb593afb9a',
                    'sharedWithArray' => Array
                        (
                        ),
        
                    'sharedVisible' => Array
                        (
                        ),
        
                    'sharedWith' => Array
                        (
                        )
        
                        ),
        
            '_id' => '61f7b60b93220a1507956fc0',
            'chatMessages' => Array
                (
                ),
        
            'screenshots' => Array
                (
                ),
        
            '__v' => 0,
            'id' => '61f7b60b93220a1507956fc0'
                ];
          
          print_r(json_encode($data,JSON_PRETTY_PRINT));
        echo"<pre>";
    }

    
}
