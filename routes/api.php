<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\gCalenderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalendarApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return response()->json(['status'=>'successfull']);
// });
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('check', function() {
        $user=Auth::user();
        return response()->json(['status'=>200,'name'=>$user->email]);
    });
    Route::get('/Calendar_events',[CalendarApiController::class,'calendar_event']);
    Route::post('/Transcription_response',[UserController::class,'transcript_respond']);
    Route::post('/Highlight_Response',[UserController::class,'Highlight_Respond']);
    Route::put('/Setting_highlight',[UserController::class,'Setting_highlight']);
    Route::put('/Updating_transcript',[UserController::class,'Updating_transcript']);
    Route::post('/Meeting_name',[UserController::class,'Meeting_name']);
    Route::get('/logout',[gCalenderController::class,'logout_savvy']);
    
});

// Route::get('/user',[UserController::class,'view']);
Route::post('/user',[UserController::class,'find_user']);
Route::post('/setmeeting',[UserController::class,'set_meeting']);
Route::post('/set_googlecal',[UserController::class,'set_googlecal']);

// ===============================Google Calendar ApI ==
Auth::routes();

Route::put('/Transcription',[UserController::class,'transcription']);
Route::put('/Highlight',[UserController::class,'set_highlight']);

   
