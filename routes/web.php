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
    return view('index');
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

Route::prefix('customer')->name('customer.')->middleware(['auth', 'checkrole:customer'])->group(function() {
    Route::get('edit/{id}', 'CustomerController@editEvent')->name('edit');
    Route::get('tickets/{id}', 'CustomerController@tickets')->name('tickets');
    Route::get('documents', 'DocumentController@documents')->name('documents');
    Route::get('stats/{id}', 'StatisticController@stats')->name('stats');
    Route::get('reservations/{id}', 'StatisticController@reservations')->name('reservations');
    Route::get('turns/{id}', 'StatisticController@turns')->name('turns');
    Route::get('scan/{id}', 'StatisticController@scan')->name('scan');
    Route::get('assistance/{id}', 'StatisticController@assistance')->name('assistance');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkrole:admin'])->group(function() {
    // Route::get('/purchases', 'CustomerController@purchases')->name('purchases');
    Route::get('documents', 'AdminController@documents')->name('documents');
    Route::get('contracts', 'AdminController@contracts')->name('contracts');
});

Route::get('/{event}/{ticket?}', 'PublicController@index')->name('/');
Route::get('payments/paymentMethod/{method}', function($method) {
    if ($method == 'card') {
        return View::make("cardPayment")->render();
    } else if ($method == 'oxxo') {
        return View::make("oxxoPayment")->render();
    }
});
Route::get('exit/discount/access', function() {
    return view('discount');
});

Route::post('makePayment', 'PublicController@makePayment');
Route::post('reference_paid', 'WebhookController@reference_paid');
Route::post('discount', 'StatisticController@discount');

// Routes usage for customers
Route::post('checkEvent', 'CustomerController@checkEvent');
Route::post('createEvent', 'CustomerController@createEvent');
Route::post('uploadImage', 'CustomerController@uploadImage');
Route::post('extractEvent', 'CustomerController@extractEvent');
Route::post('updateNameWebsite', 'CustomerController@updateNameWebsite');
Route::post('deleteLogo', 'CustomerController@deleteLogo');
Route::post('editDescription', 'CustomerController@editDescription');
Route::post('editDates', 'CustomerController@editDates');
Route::post('addContact', 'CustomerController@addContact');
Route::post('addLocation', 'CustomerController@addLocation');
Route::post('saveTicket', 'CustomerController@saveTicket');
Route::post('deleteTicket', 'CustomerController@deleteTicket');
Route::post('searchEvents', 'CustomerController@searchEvents');
Route::post('extractSales', 'StatisticController@extractSales');
Route::post('resetPassword', 'DocumentController@resetPassword');
Route::post('editAccount', 'DocumentController@editAccount');
Route::post('detailsSale', 'StatisticController@detailsSale');
Route::post('chargingGraphic', 'StatisticController@chargingGraphic');
Route::post('saveTaxData', 'DocumentController@saveTaxData');
Route::post('saveBankData', 'DocumentController@saveBankData');
Route::post('uploadDocuments', 'DocumentController@uploadDocuments');
Route::post('changeStatusEvent', 'CustomerController@changeStatusEvent');
Route::post('resendTickets', 'CustomerController@resendTickets');
Route::post('saveTurns', 'CustomerController@saveTurns');
Route::post('model_payment', 'CustomerController@model_payment');
Route::get('excel/downloadPayments/{id}', 'StatisticController@downloadPayments')->name('excel/downloadPayments');
Route::post('searchAccess', 'StatisticController@searchAccess');
Route::post('validateFolio', 'StatisticController@validateFolio');
Route::post('extractAssistence', 'StatisticController@extractAssistence');

// Routes usage for admin
Route::post('extractUsersDocuments', 'AdminController@extractUsersDocuments');
Route::post('statusDocument', 'AdminController@statusDocument');
Route::post('extractUsersInfo', 'AdminController@extractUsersInfo');
Route::post('uploadContract', 'AdminController@uploadContract');
Route::post('deleteContract', 'AdminController@deleteContract');