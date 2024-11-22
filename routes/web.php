<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyConverterController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/rates', [CurrencyConverterController::class, 'getRates']);
