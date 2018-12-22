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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/menu/{request}', 'CustomerController@vendor');

Route::post('/addOffer', 'OfferController@addOffer');
Route::post('/apply', 'CouponController@apply');
Route::post('/getoffer', 'OfferController@apply');
Route::post('/discount', 'OfferController@instant');
Route::post('/addinstant', 'OfferController@addinstant');

Route::resource('offer', 'OfferController');
Route::resource('coupon', 'CouponController');

Route::resource('vendor', 'VendorController');
Route::resource('food', 'FoodController');
Route::resource('customer', 'CustomerController');
Route::resource('cart', 'CartController');
Route::resource('order', 'OrderController');
