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

Route::get('/', function () {
    return view('index');
})->middleware('checkActiveUser');

Route::get('auth/confirmation', function(){
    return view('front.confirmation');

})->middleware('confirm-auth');

Route::group(['middleware' => 'auth'], function(){

    Route::get('settings', 'UsersController@settings');
    Route::get('finance', 'FinanceController@show');
    Route::get('messages', 'MessagesController@show');

});

Route::get('requests/{id}/edit', 'RequestsController@edit')->middleware('auth');
Route::get('requests/{id}', 'RequestsController@getRequest')->where('id', '[0-9]+');
Route::get('requests/{action?}', 'RequestsController@show');

//temporary
//Route::get('messages', function(){
//    return view('temporary.messages');
//});

Route::get('alerts', function(){
    return view('temporary.alerts');
});
Route::get('alert-page', function(){
    return view('temporary.alert-page');
});
Route::get('request', function(){
    return view('temporary.request');
});
Route::get('finance-doer', function(){
    return view('temporary.finance-doer');
});
Route::get('alert-doer', function(){
    return view('temporary.alert-doer');
});



Route::get('auth/forgot', function(){
    return view('front.forgot');
});


Route::group(['middleware' => ['auth', 'checkActiveUser']], function() {

    Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin'], function() {

        Route::get('moderator/{action}', 'Admin\ModeratorController@show');

        Route::resource('users', 'Admin\UsersController');
//        Route::get('roles/delete/{id}', 'Admin\RolesController@destroy');
        Route::resource('roles', 'Admin\RolesController');
        Route::resource('permissions', 'Admin\PermissionsController');
        Route::resource('/', 'Admin\AdminController');

        Route::get('geo/city/{id}/delete', 'Admin\GeoController@deleteCity');
        Route::post('geo/city/{id}', 'Admin\GeoController@updateCity');
        Route::get('geo/city/{id}', 'Admin\GeoController@showCity');
        Route::resource('geo', 'Admin\GeoController');

        Route::resource('topics', 'Admin\TopicsController');
        Route::resource('objects', 'Admin\ObjectsController');
        Route::resource('sources', 'Admin\SourcesController');

    });
});


Route::get('logout', 'Auth\LoginController@logout');
