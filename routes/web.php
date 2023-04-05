<?php

use Illuminate\Support\Facades\Route;

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
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Route::get('/google_sign_in/redirect', 'App\Http\Controllers\Authentication@signInWithGoogleRedirect');
// Route::get('/google_sign_in/callback', 'App\Http\Controllers\Authentication@signInWithGoogleCallback');