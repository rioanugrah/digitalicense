<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::domain(parse_url(env('APP_URL'), PHP_URL_HOST))->group(function () {
    Route::post('callback',[App\Http\Controllers\Payment\TripayController::class, 'handle']);
    // Route::post('callback','Payment\TripayController@handle');
    // Route::post('testing',function(){
    //     return 'ok';
    // });
    Route::get('setwebhook',[App\Http\Controllers\BotTelegramController::class, 'setWebhook']);
    Route::post('webhook',[App\Http\Controllers\BotTelegramController::class, 'commandHandlerWebHook']);
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
