<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Meeting_highlight;
use App\Models\Meeting_transcript;
use Illuminate\Support\Facades\DB;
use Auth;
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

    public function Highlight_Respond(Request $request){
        $user=Auth::user();
        $meeting_link=$request->meeting_link;
        $meeting=Meeting::where("external_meeting_id", '=',$meeting_link)->first();
        if(!$meeting){
            $Error=['No Transcription found.'];
            return response()->json(['Transcript'=>$Error]);
        }
        $user_id=$user->id;
        $meeting_id=$meeting->id;
        $meeting_date=date("Y-m-d",strtotime($request->date));
        $meeting_transcript=Meeting_transcript::select('meeting_transcript.id','speaker_name','transcript_text','moment_type')->join('meeting_highlight','meeting_transcript.id','=','meeting_highlight.transcript_text_id')->where('meeting_id', '=',$meeting_id)->where('user_id','=',$user_id)
        ->where('meeting_transcript.created_at',"LIKE","{$meeting_date}%")->get();
        
        if(sizeof($meeting_transcript)){
            return response()->json(['Transcript'=>$meeting_transcript]);
        }
        else{
            $Error=['No Action/Highlight text found.'];
            return response()->json(['Transcript'=>$Error]);
        }
    }

    public function transcription(Request $request){
        $user_id=$request->id;
        $tags=$request->transcript['tags'];
        $transcript_text=$request->transcript['text'];
        $speaker_name=$request->transcript['speaker']['name'];
        $start=date("Y-m-d h:i:s",strtotime($request->transcript['duration']['start']));
        $end=date("Y-m-d h:i:s",strtotime($request->transcript['duration']['end']));
        try {
       
            // ================Transcription Saving============
            $meeting=Meeting::where("external_meeting_id", '=',$request->transcript['meeting_id'])->first();
            if(!$meeting){
                $meeting=new Meeting();
                $meeting->meeting_owner_id=$user_id;
                $meeting->external_meeting_id=$request->transcript['meeting_id'];
                $meeting->external_meeting_name=$request->transcript['meeting_name'];
                $meeting->save();
            }
    
            
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
            if(sizeof($tags)>0){
                $meeting_highlight=Meeting_highlight::where('transcript_text_id','=',$meeting_transcript->id)->get();
                if(sizeof($meeting_highlight)>0){
                    foreach ($meeting_highlight as $key => $value) {
                        $value->delete();
                    }
                }
                foreach ($tags as $key => $value) {
                    $meeting_highlight=new Meeting_highlight();
                    $meeting_highlight->moment_type=$value['type'];
                    $meeting_highlight->transcript_text_id=$meeting_transcript->id;
                    $meeting_highlight->save();
                }
            }
            return response()->json(["Function"=>"Try function"]);
           
        }
        // ==========Highlight Setting====================
        catch (\Throwable $th) {
            //throw $th;
        
            $external_meeting=$tags['0']['meeting_id'];
            $meeting=Meeting::where("external_meeting_id", '=',$external_meeting)->first();
            $highlight_text=$tags['0']['transcript']['text'];
            $meeting_transcript=Meeting_transcript::where('meeting_id', '=',$meeting->id)->where('transcript_text','=',$highlight_text)->orderBy("id","desc")->first();
            if($meeting_transcript){
                $meeting_highlight=Meeting_highlight::where('transcript_text_id','=',$meeting_transcript->id)->first();
                if(!$meeting_highlight){
                    $meeting_highlight=new Meeting_highlight();
                    $meeting_highlight->moment_type=$tags['0']['type'];
                    $meeting_highlight->transcript_text_id=$meeting_transcript->id;
                    $meeting_highlight->save();
                }
            }
            return response()->json(["Error"=>$th]);

        }
       
    }

    public function Meeting_name(Request $request){
        $meeting_link=$request->meeting_link;
        $meeting=Meeting::where("external_meeting_id", '=',$meeting_link)->first();
        if(!$meeting){
            return response()->json(['meeting_name'=>'Untitled Meeting']);
        }
        else{
            return response()->json(['meeting_name'=>$meeting->external_meeting_name]);

        }
    }

    public function transcript_respond(Request $request){
        $user=Auth::user();
        $meeting_link=$request->meeting_link;
        $meeting=Meeting::where("external_meeting_id", '=',$meeting_link)->first();
        if(!$meeting){
            $Error=['No Transcription found.'];
            return response()->json(['Transcript'=>$Error]);
        }
        $user_id=$user->id;
        $meeting_id=$meeting->id;
        $meeting_date=date("Y-m-d",strtotime($request->date));
        $meeting_transcript=Meeting_transcript::where('meeting_id', '=',$meeting_id)->where('user_id','=',$user_id)->where('created_at',"LIKE","{$meeting_date}%")->get();
        
        if(sizeof($meeting_transcript)){
            return response()->json(['Transcript'=>$meeting_transcript]);
        }
        else{
            $Error=['No Transcription found because you had not attended this meeting.'];
            return response()->json(['Transcript'=>$Error]);
        }
    }

    public function checking(){
        $date="20220330T195959Z";
        echo date("Y-m-d",strtotime($date));
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
