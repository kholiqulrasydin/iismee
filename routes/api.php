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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group( function () {
    
    Route::get('/test', function(){
        return response()->json('Successfully authenticated', 200);
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('/data', 'App\Http\Controllers\MediaController@getUserData');
    });

    Route::group(['prefix' => 'media'], function() {
        Route::post('/upload', 'App\Http\Controllers\MediaController@upload_media');
        Route::post('/download', 'App\Http\Controllers\MediaController@fetch_media');
        Route::post('/delete', 'App\Http\Controllers\MediaController@delete');
    });

    Route::group(['prefix' => 'presention'], function() {
        Route::get('/fetch', 'App\Http\Controllers\PresentionController@fetch_all');
        Route::post('/present', 'App\Http\Controllers\PresentionController@add_presention');
        Route::post('/delete', 'App\Http\Controllers\PresentionController@delete');
    });

    Route::group(['prefix' => 'proposal'], function() {
        Route::get('/fetch', 'App\Http\Controllers\ProposalController@fetch_data');
        Route::post('/store', 'App\Http\Controllers\ProposalController@store');
        Route::post('/update', 'App\Http\Controllers\ProposalController@update');
    });

    Route::group(['prefix' => 'activity'], function() {
        Route::get('/delete', 'App\Http\Controllers\ActivityController@delete');
        Route::post('/store', 'App\Http\Controllers\ActivityController@store');
        Route::get('/check', 'App\Http\Controllers\ActivityController@check_if_exists');
    });

});


Route::post('/login', 'App\Http\Controllers\Authentication@login');
Route::post('/register', 'App\Http\Controllers\Authentication@register');
