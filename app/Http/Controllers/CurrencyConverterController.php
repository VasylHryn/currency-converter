<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyConverterController extends Controller
{
    public function getRates()
    {
        $response = Http::withOptions(['verify' => false])
            ->get('https://api.exchangerate-api.com/v4/latest/USD');

        return response()->json($response->json());
    }

    public function convertCurrency(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);


        $response = Http::withOptions(['verify' => false])
            ->get('https://api.exchangerate-api.com/v4/latest/USD');

        if (!$response->successful()) {
            return response()->json(['error' => 'Ошибка при получении данных'], $response->status());
        }

        $rates = $response->json()['rates'];


        if (!isset($rates[$request->from]) || !isset($rates[$request->to])) {
            return response()->json(['error' => 'Неверная валюта'], 400);
        }

        $amountInUSD = $request->amount / $rates[$request->from];
        $convertedAmount = $amountInUSD * $rates[$request->to];
        return response()->json([
            'from' => $request->from,
            'to' => $request->to,
            'amount' => $request->amount,
            'converted_amount' => round($convertedAmount, 2),
        ]);
    }
}
