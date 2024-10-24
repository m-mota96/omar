<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Event;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('assistence/addAssistence', 'StatisticController@addAssistenceExternal');
Route::get('eventos', function() {
    $events = Event::all();
    return response()->json($events);
});
Route::get('cronjob/{activitie}', 'CronjobController@cronjob');
Route::post('searchCodes', 'CronjobController@searchCodes');