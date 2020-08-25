<?php

use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->namespace('Panel')->group(function (){

    // get single page application
    Route::get('/panel', 'AppController')->name('panel');

    // code crud
    Route::resource('/codes', 'CodeController')->except(['create', 'edit']);

    // get customers for the specified code
    Route::get('/codes/{code}/customers', 'CodeController@customers')->name('codes.customers');

});
