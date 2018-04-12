<?php

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

use App\Events\ChatMessage;
use Illuminate\Support\Facades\Event;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/message', function () {
  Event::dispatch(new ChatMessage('New Chat Message'));
//  event(new ChatMessage());
});

Route::group(['middleware' => 'auth'], function () {
  Route::get('chat', function() {
    return view('chatvue');
  });
  Route::post('/chat_message', 'ChatMessageController@store');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
