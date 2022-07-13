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
    return view('welcome');
});

Route::get('message/total', [\App\Http\Controllers\Message::class,'total'])->name('message_total');
Route::post('message/ajax-total', [\App\Http\Controllers\Message::class,'getDataForChart'])->name('ajax_message_total');
Route::get('message/user-activity', [\App\Http\Controllers\Message::class,'userActivity'])->name('user_activity');
