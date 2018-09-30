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

Route::get('/login', function (Request $request) {
    return view('welcome');
})->name('login');

Route::get('/', function (Request $request) {

    $prepareCurrencies = [];
    $currencies = new Collection(Api::getCurrencies());
    $rates = Api::getRates();

    foreach ($rates as $rate) {
        $prepareCurrencies[] = [
            'from' => $currencies->where('curr_id', $rate['from'])->first(),
            'to' => $currencies->where('curr_id', $rate['to'])->first(),
            'rate' => $rate,
        ];
    }

    return view('listCurrency', ['currencies' => $prepareCurrencies]);
})->name('currencies');
