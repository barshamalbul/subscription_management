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
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/plans', 'plansController@index')->name('plans.index');

Route::get('/suscribed/{id}', 'SubscriptionController@index')->name('subscription.index');
Route::get('/show_subscription/{id}', 'SubscriptionController@show')->name('subscription.show');
Route::post('/change/{id}', 'SubscriptionController@change')->name('subscription.change');

Route::get('/suscribed/cancel/{id}', 'SubscriptionController@destroy')->name('subscription.cancel');
Route::get('/suscribed/increase/{id}', 'SubscriptionController@increase')->name('subscription.increase');
Route::get('/suscribed/decrease/{id}', 'SubscriptionController@decrease')->name('subscription.decrease');

Route::get('/subscription/{id}', 'plansController@store')->name('plans.store');
Route::post('/subscription/subscribe','plansController@subscribe')->name('plans.subscribe');

});