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

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

Route::get('/', 'CurrencyController@currencies')->name('currencies');
Route::get('/login', 'Controller@login')->name('login');
Route::get('/oauth-redirect-handler', 'Controller@oauthRedirectHandler')->name('oauthRedirectHandler');
