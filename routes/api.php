<?php

use Illuminate\Http\Request;

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

Route::post('auth/register', 'Api\AuthController@register')->middleware('guest');
Route::post('auth/login', 'Api\AuthController@login')->middleware('guest');
Route::post('auth/logout','Api\AuthController@logout')->middleware('auth');
Route::post('auth/confirmation-repeat', 'Api\AuthController@confirmationRepeat');
Route::post('auth/confirmation', 'Api\AuthController@confirmation');

Route::post('auth/forgot', 'Auth\ForgotPasswordController@getResetToken');
Route::post('auth/reset', 'Auth\ResetPasswordController@reset');

Route::post('requests/set/{action}', 'Api\ClaimsController@setPost')->middleware('auth');
Route::post('requests/search', 'Api\ClaimsController@search');
Route::get('requests/guest', 'Api\ClaimsController@guestRequests');
Route::get('requests/get-request', 'Api\ClaimsController@getRequest');
Route::get('requests/{action}', 'Api\ClaimsController@getAction')->middleware('auth');
Route::post('requests/{action}', 'Api\ClaimsController@postAction')->middleware('auth');

Route::group(['middleware' => 'auth'], function(){

    Route::post('users/{action}', 'Api\UsersController@postIndex');
    Route::get('users/{action}', 'Api\UsersController@index');

    Route::post('finance/deposit', 'Api\FinanceController@deposit');
    Route::post('finance/withdraw', 'Api\FinanceController@withdraw');
    Route::get('finance/{action}', 'Api\FinanceController@show');

    Route::get('messages/unread', 'Api\MessagesController@unread');
    Route::get('messages/{type}', 'Api\MessagesController@getMessages');
    Route::post('messages/send', 'Api\MessagesController@sendMessage');
});