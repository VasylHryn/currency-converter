<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyConverterController;

Route::get('/currency-rates', [CurrencyConverterController::class, 'getRates']);
Route::post('/convert-currency', [CurrencyConverterController::class, 'convertCurrency']);

Route::get('/', function () {
    return view('welcome');
});
