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

Route::prefix('v1')->namespace('Api\V1')->group(function () {

    // take part in competition
    Route::post('competition/take-part', 'CompetitionController@takePart')->name('competition.take.part');

    // check phone is winner
    Route::get('competition/winner', 'CompetitionController@winner')->name('competition.winner');
});
