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

Auth::routes(['verify' => true]);
// // Authentication Routes...
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home/{search?}', 'HomeController@index')->name('home')->middleware(['auth', 'verified']);

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkrole:customer'])->group(function() {
    Route::get('edit/{id}', 'AdminController@editEvent')->name('edit');
    Route::get('tickets/{id}', 'AdminController@tickets')->name('tickets');
    Route::get('documents', 'DocumentController@documents')->name('documents');
    Route::get('stats/{id}', 'StatisticController@stats')->name('stats');
    Route::get('reservations/{id}', 'StatisticController@reservations')->name('reservations');
});

Route::prefix('customer')->name('customer.')->middleware(['auth', 'checkrole:customer'])->group(function() {
    Route::get('/purchases', 'CustomerController@purchases')->name('purchases');
});

Route::get('/{event}/{ticket?}', 'PublicController@index')->name('/');
Route::get('/paymentMethod/{method}', function($method) {
    if ($method == 'card') {
        return View::make("cardPayment")->render();
    } else if ($method == 'oxxo') {
        return View::make("oxxoPayment")->render();
    }
});
Route::post('makePayment', 'PublicController@makePayment');
Route::post('reference_paid', 'WebhookController@reference_paid');

// Routes usage for admin
Route::post('checkEvent', 'AdminController@checkEvent');
Route::post('createEvent', 'AdminController@createEvent');
Route::post('uploadImage', 'AdminController@uploadImage');
Route::post('extractEvent', 'AdminController@extractEvent');
Route::post('updateNameWebsite', 'AdminController@updateNameWebsite');
Route::post('deleteLogo', 'AdminController@deleteLogo');
Route::post('editDescription', 'AdminController@editDescription');
Route::post('editDates', 'AdminController@editDates');
Route::post('addContact', 'AdminController@addContact');
Route::post('addLocation', 'AdminController@addLocation');
Route::post('saveTicket', 'AdminController@saveTicket');
Route::post('deleteTicket', 'AdminController@deleteTicket');
Route::post('searchEvents', 'AdminController@searchEvents');
Route::post('extractSales', 'StatisticController@extractSales');
Route::post('resetPassword', 'DocumentController@resetPassword');
Route::post('editAccount', 'DocumentController@editAccount');
Route::post('detailsSale', 'StatisticController@detailsSale');