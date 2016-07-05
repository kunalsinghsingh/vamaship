<?php

App::bind('Repositories\UserInterface', 'Repositories\Eloquent\UserRepo');
App::bind('Repositories\BookingInterface', 'Repositories\Eloquent\BookingRepo');
/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/login', function() {
    return View::make('auth/login');
});
Route::get('/', function() {
    return View::make('auth/login');
});
Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'Admin\AdminDashboardController@index'));
Route::post('/userLogin', array('as' => 'userLogin', 'uses' => 'Admin\UserController@userLogin'));
Route::get('/logout', array('as' => 'logout', 'uses' => 'Admin\UserController@userLogout'));


//Manage User
Route::get('/user/add/{id?}', array('as' => 'addUser', 'uses' => 'Admin\AdminUserController@getUserProfile'));

Route::get('/user/edit/{id?}', array('as' => 'editUser', 'uses' => 'Admin\AdminUserController@getUserProfile'));

Route::post('/user/saveProfile', array('as' => 'saveProfile', 'uses' => 'Admin\AdminUserController@saveProfile'));
Route::post('/deleteUser/{id?}', array('as' => 'deleteUser', 'uses' => 'Admin\AdminUserController@deleteUser'));
Route::get('/userListing', 'Admin\AdminUserController@userListing');

Route::get('/profile', array('as' => 'profile', 'uses' => 'Admin\AdminProfileController@getUserProfile'));
Route::get('/user', array('as' => 'user', 'uses' => 'Admin\AdminUserController@index'));






//Manage Book Address
Route::get('/booking', array('as' => 'booking', 'uses' => 'Admin\BookingController@index'));
Route::get('/addbooking', array('as' => 'addbooking', 'uses' => 'Admin\BookingController@create'));
Route::post('/booking/save', array('as' => 'addsave', 'uses' => 'Admin\BookingController@save'));

Route::get('/booking/edit/{id?}', array('as' => 'editBooking', 'uses' => 'Admin\BookingController@edit'));
Route::post('/booking/update/{id?}', array('as' => 'update', 'uses' => 'Admin\BookingController@update'));

Route::get('/booking/delete/{id?}', array('as' => 'bookingdelete', 'uses' => 'Admin\BookingController@delete'));









//APIUserController
Route::post('/api/v1/auth', array('as' => 'auth', 'uses' => 'API\APIAuthController@userAuthentication'));
Route::get('/api/v1/getbook', array('as' => 'getbook', 'uses' => 'API\APIDataController@index'));
