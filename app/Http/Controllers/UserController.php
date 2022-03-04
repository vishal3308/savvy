<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function view(){
        $user= User::with('getcompany')->get();
        echo"<pre>";
        print_r(json_encode($user,JSON_PRETTY_PRINT));
        echo"<pre>";
    }
    public function set_meeting(Request $request){
        // echo"<pre>";
        // print_r($request['meeting_owner']);
        // echo"<pre>";
        $meeting=new Meeting();
        $meeting->meeting_owner_id=$request['meeting_owner'];
        $meeting->external_meeting_id=$request['callId'];
        $meeting->external_meeting_name=$request['name'];
        $meeting->save();
        echo "Successfull";
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

    public function testing(Request $request){
        echo "<pre>";
        print("Welcome in User Controller");
        // print_r($request);
        echo "</pre>";
        $data=$request->all();
        return view('user',['test'=>$data]);
    }
}
