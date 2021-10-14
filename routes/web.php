<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Message;
use App\Http\Controllers\UserAuthController;  
use App\Http\Controllers\GeoLocationController;
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

Route::get('/', function () {
    return view('index');
});


// Route::post("send-message",function(Request $request){
//     event(new Message($request->input('username'),$request->input('message')));
//     return ["success" =>true];
// });

Route::post("send-message",function(Request $request){
    event(new Message($request->input('username'),$request->input('message')));
    return ["success" =>true];
});

//Route::get("users",)
//Route::get('/status', 'UserController@userOnlineStatus');
Route::get('userstatus', [UserAuthController::class,'userOnlineStatus']);
//Route::resource('categories',CategoriesController::class);

 
Route::get('get-address-from-ip', [GeoLocationController::class, 'index']);

