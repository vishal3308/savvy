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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user',[UserController::class,'view']);
Route::post('/setmeeting',[UserController::class,'set_meeting']);
Route::post('/user',[UserController::class,'find_user']);

// ===============================Google Calendar ApI ==
Auth::routes();

Route::get('/Calendar_events/{id}',[CalendarApiController::class,'calendar_event']);

// Route::group(['namespace' => 'API'], function() {
//     // Private routes (auth required)
//     Route::group(['middleware' => 'auth:api'], function() {
//         Route::get('calendar_index', function(){
//             return auth()->user();
//         }); 
       
//     });

// });
   
