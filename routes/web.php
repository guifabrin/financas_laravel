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
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('accounts', 'AccountController');
Route::get('/accounts/{id}/confirm', 'AccountController@confirm');
Route::get('account/{accountId}/transactions', 'TransactionController@index');
Route::get('account/{accountId}/invoices', 'TransactionController@invoices');
Route::get('account/{accountId}/transaction/create', 'TransactionController@create');
Route::get('account/{accountId}/transaction/{transactionId}/edit', 'TransactionController@edit');
Route::get('account/{accountId}/transaction/{transactionId}/confirm', 'TransactionController@confirm');
Route::post('account/{accountId}/transaction', 'TransactionController@store');
Route::put('account/{accountId}/transaction/{transactionId}', 'TransactionController@update');
Route::delete('account/{accountId}/transaction/{transactionId}', 'TransactionController@destroy');