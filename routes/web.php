<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\gCalenderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalendarApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/user',[UserController::class,'view']);
Route::get('/set',function(){
 return view('user');
});

// ============Google Authentication Routes============
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Google login
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);
// Google Calender Api Route
Route::resource('gcalendar',gCalenderController::class);
Route::get('oauth',[gCalenderController::class,'oauth'])->name('oauthCallback');
Route::get('cal_index',[gCalenderController::class, 'index'])->name('cal.index');
Route::get('calendar_index',function(){
    return view('calendar_list');
});

Route::get('Calendar_events/{id}',[CalendarApiController::class,'calendar_event']); //Api===


//===When I Login then api automattically set while when I simplly close the browser and Reopen then I was logged in
// but I can't fetch calender api