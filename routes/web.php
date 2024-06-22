<?php

use App\Http\Controllers\Organizer\DashboardController;
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
    return redirect()->route('web.events.index');
});

Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register');

Route::prefix('/organizer')->name('organizer.')->group(function () {
    Route::get('/', 'App\Http\Controllers\Organizer\DashboardController@index')->name('index')->middleware('auth');
    Route::prefix('/events')->name('events.')->group(function () {
        Route::get('/create', 'App\Http\Controllers\Organizer\EventsController@create')->name('create');
        Route::post('/', 'App\Http\Controllers\Organizer\EventsController@createPost')->name('createPost');
        Route::get('/update/{id}', 'App\Http\Controllers\Organizer\EventsController@update')->name('update');
        Route::post('/update/{id}', 'App\Http\Controllers\Organizer\EventsController@updatePost')->name('updatePost');
        Route::get('/delete/{id}', 'App\Http\Controllers\Organizer\EventsController@delete')->name('delete');
        Route::get('/close/{id}', 'App\Http\Controllers\Organizer\EventsController@closeEvent')->name('close');
    });
});
Route::prefix('/web')->name('web.')->group(function () {
    Route::prefix('/events')->name('events.')->group(function () {
        Route::get('/', 'App\Http\Controllers\Web\EventsController@index')->name('index');
        Route::get('/show/{id}', 'App\Http\Controllers\Web\EventsController@show')->name('show');
        Route::get('/cart', 'App\Http\Controllers\Web\EventsController@cart')->name('cart')->middleware('auth');
        Route::post('/cartupdate', 'App\Http\Controllers\Web\EventsController@updateCart')->name('updateCart')->middleware('auth');
        Route::post('/buyTicket/{eventId}/{categoryId}', 'App\Http\Controllers\Web\EventsController@buyTicket')->name('buyTicket');
        Route::post('/confirm-purchase', 'App\Http\Controllers\Web\EventsController@confirmPurchase')->name('confirmPurchase');
    });
});
