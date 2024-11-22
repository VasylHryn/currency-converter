<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyConverterController extends Controller
{
    public function getRates()
    {
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
        return response()->json($response->json());
    }
}
