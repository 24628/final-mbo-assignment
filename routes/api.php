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
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');
Route::get('selectable-roles', 'RoleController@showSelectables');
Route::post('reset-password', 'Auth\ForgotPasswordController@sendPasswordResetLink');
Route::post('reset/password', 'Auth\ResetPasswordController@callResetPassword');
Route::get('/event-overview/{event}', 'OverviewController@index')->name('event.overview');
Route::get('/event-overview', 'OverviewController@event')->name('event');
Route::get('/event/map/{event}', 'OverviewController@map')->name('event.map');
Route::get('/user/login-check', 'UserController@isLoggedIn');
Route::get('/event/tickets/{id}', 'EventController@tickets');

Route::group(['middleware' => ['auth:api', 'api_token_valid']], function () {
    Route::get('/print/badge/{id}', 'UserController@print');
    Route::get('/is-subscribed/{event}', 'UserController@isSubscribed');
    Route::get('/map/register/', 'MapController@isAllowedToRegister');
    Route::patch('/event/map/subscribe/{event}', 'MapController@subscribe');

    Route::post('logout', 'Auth\LoginController@logout');
    Route::patch('selectable-role-edit', 'UserController@UpdateSelectableUserRole');

    Route::get('/refresh-token','UserController@updatetoken');
    Route::post('/search/profile','UserController@search');
    Route::get('/permissions', 'UserController@permissions')->name('user.permissions');
    Route::patch('/user/name/{user}', 'UserController@update')->name('user.name');

    Route::get('event', 'EventController@index')->name('event');
    Route::get('event/{event}', 'EventController@show')->name('event.show');
    Route::post('event', 'EventController@store')->name('event.store');
    Route::patch('event/{event}', 'EventController@update')->name('event.update');
    Route::delete('event/{event}', 'EventController@destroy')->name('event.destroy');

    Route::post('event/subscribe/', 'EventController@subscribe')->name('event.subscribe.store');
    Route::delete('event/unsubscribe/{event}', 'EventController@unsubscribe')->name('event.subscribe.destroy');

    Route::get('event-settings', 'EventSettingsController@index')->name('event-settings');
    Route::get('event-settings/{id}', 'EventSettingsController@show')->name('event-settings.show');
    Route::post('event-settings', 'EventSettingsController@store')->name('event-settings.store');
    Route::patch('event-settings/{id}', 'EventSettingsController@update')->name('event-settings.update');
    Route::delete('event-settings/{event}', 'EventSettingsController@destroy')->name('event-settings.destroy');

    Route::get('profile', 'ProfileController@index')->name('profile');
    Route::get('/register/event/user/{id}', 'ProfileController@event')->name('profile');
    Route::get('profile/{profile}', 'ProfileController@show')->name('profile.show');
    Route::post('profile', 'ProfileController@store')->name('profile.store');
    Route::patch('profile/{profile}', 'ProfileController@update')->name('profile.update');
    Route::delete('profile/{profile}', 'ProfileController@destroy')->name('profile.destroy');

    Route::get('profile-check','ProfileController@check')->name('profile.check');

    Route::get('profile-cv/{profile}', 'ProfileController@showcv')->name('profile.cv.show');
    Route::post('profile-cv', 'ProfileController@storecv')->name('profile.cv.store');
    Route::patch('profile-cv/update/{profile}','ProfileController@updatecv')->name('profile.cv.update');

    Route::get('role', 'RoleController@index')->name('role');
    Route::get('role/{role}', 'RoleController@show')->name('role.show');
    Route::get('role-update-selectable/{role}', 'RoleController@updateSelectable')->name('role.update.selectable');
    Route::post('role', 'RoleController@store')->name('role.store');
    Route::patch('role/{role}', 'RoleController@update')->name('role.update');
    Route::delete('role/{role}', 'RoleController@destroy')->name('role.destroy');

    Route::get('item', 'ItemController@index')->name('item');
    Route::get('item/{item}', 'ItemController@show')->name('item.show');
    Route::post('item', 'ItemController@store')->name('item.store');
    Route::patch('item/{item}', 'ItemController@update')->name('item.update');
    Route::delete('item/{item}', 'ItemController@destroy')->name('item.destroy');

    Route::get('map', 'MapController@index')->name('map');
    Route::get('map/{id}', 'MapController@show')->name('map.show');
    Route::post('map', 'MapController@store')->name('map.store');
    Route::patch('map/{map}', 'MapController@update')->name('map.update');
    Route::delete('map/{map}', 'MapController@destroy')->name('map.destroy');

    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/{event}', 'AdminController@event');
    Route::get('/admin/user/{user}', 'AdminController@user');
    Route::get('/admin/program/{id}', 'AdminController@program');
    Route::get('/admin/congress/{id}', 'AdminController@congress');
    Route::get('/admin/notify', 'AdminController@forceNotify');
    Route::post('/admin/excel', 'AdminController@excel');
    Route::post('/admin/search', 'AdminController@search');
    Route::patch('/admin/update/user/{user}', 'AdminController@updateUser');
    Route::delete('/admin/user/{user}', 'AdminController@deleteUser');

    Route::get('/program-item/{id}', 'ProgramItemsController@show')->name('program.item.show');
    Route::post('/program-item', 'ProgramItemsController@store')->name('program.item.store');
    Route::patch('/program-item/{id}', 'ProgramItemsController@update')->name('program.item.update');
    Route::delete('/program-item/{id}', 'ProgramItemsController@destroy')->name('program.item.destroy');

    Route::get('/congress/{congress}', 'CongressController@show');
    Route::post('/congress', 'CongressController@store');
    Route::patch('/congress/{congress}', 'CongressController@update');
    Route::delete('/congress/{congress}', 'CongressController@destroy');

    Route::get('/program/{program}', 'ProgramController@show');
    Route::post('/program', 'ProgramController@store');
    Route::patch('/program/{program}', 'ProgramController@update');
    Route::delete('/program/{program}', 'ProgramController@destroy');

    Route::get('/block/{block}', 'BlockController@show');
    Route::post('/block', 'BlockController@store');
    Route::patch('/block/{block}', 'BlockController@update');
    Route::delete('/block/{block}', 'BlockController@destroy');
});







