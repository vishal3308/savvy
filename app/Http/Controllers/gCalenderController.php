<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use App\Models\Google_calendar;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class gCalenderController extends Controller
{
    protected $client;

    public function __construct()
    {   
        
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
        
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $results = $service->events->listEvents($calendarId);
            $cal_events=$results->getItems();
            // return $cal_events;
                foreach($cal_events as $event){
                    //====Checking for Google Meet or Zoom Event only
                    if(str_contains($event->location,"web.zoom.us") ||str_contains($event->hangoutLink,"meet.google.com") ){
                    
                        if(str_contains($event->location,"web.zoom.us")){
                            $meeting_plateform="Zoom Meeting";
                            $meeting_link=$event->location;
                        }
                        else{
                            $meeting_plateform="Google Meet";
                            $meeting_link=$event->conferenceData->conferenceId;
                        }
                        //Attendee Email storing in an array...
                        $Attendees=$event->attendees; 
                        $Attendee_email=[];
                        foreach($Attendees as $attendee){
                            array_push($Attendee_email,$attendee->email);
                        }
                        //===Checking Recurrence is a array or null
                        if(is_array($event->recurrence)){
                            $recurrence=implode(",",$event->recurrence);
                        }
                        else{
                            $recurrence=$event->recurrence;
                        }
                        $user_id=Auth::user()->id;
                        $Events=Google_calendar::where('Event_id', '=',$event->id)->first();
                        if($Events){
                            $Events->delete();
                        }
                        $Events= new Google_calendar();      
                        $Events->Event_id=$event->id;
                        $Events->Meeting_plateform=$meeting_plateform;
                        $Events->Meeting_link=$meeting_link;
                        $Events->user_id=$user_id;
                        $Events->Organizer=$event->organizer->email;
                        $Events->Attendees=implode(",",$Attendee_email);
                        $Events->Recurrence=$recurrence;
                        $Events->Summary=$event->getSummary();
                        $Events->Description=$event->getDescription();
                        $Events->Starting_time=$event->getStart()->getDateTime();
                        $Events->Ending_time=$event->getEnd()->getDateTime();
                        $Events->save();
                }
            }
            $user=Auth::user();
            $token=  $user->createToken($user->email.'_Token')->plainTextToken;
            $_SESSION['token']=$token;
            $user_name=$user->email;
            $_SESSION['User_name']=$user_name;
           
            //   return json_encode($Events);
            // return view('Calendar_list');           
            return redirect("http://localhost:3000/?user={$user_name}&token={$token}");

        } else {
            return redirect()->route('oauthCallback');
        }

    }

    public function oauth()
    {
        session_start();

        $rurl = action([gCalenderController::class,'oauth']);
        $this->client->setRedirectUri($rurl);
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            return redirect()->route('cal.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function watch_request()
    {   session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $user_email=Auth::user()->email;
            $user_auth_token=$_SESSION['access_token']['access_token'];
            $user_id=Auth::user()->id;
            $response = Http::withToken($user_auth_token)->post('https://www.googleapis.com/calendar/v3/calendars/'.$user_email.'/events/watch',
                [
                    "id"=>$user_id, // Your channel ID.
                    "type"=> "web_hook",
                    "address"=> "http://127.0.0.1:8000/notifications" // Your receiving URL.
                ]);
             
                return $response->body();
        }
        else{
            return redirect()->route('oauthCallback');
        }
    }

    public function notifications(Request $request){
        return $request->all();
    }
}