<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\ClientAuthService;
use Illuminate\Support\Collection;

class CurrencyController extends Controller
{
    public function currencies(ClientAuthService $authService, ApiService $apiService)
    {
        $prepareCurrencies = [];
        $sid = $apiService->getSid();

        if (!$sid) {
            return redirect()->route('login');
        }

        session(['sid' => $sid]);

        $currencies = new Collection($apiService->getCurrencies());
        $rates = $apiService->getRates();

        foreach ($rates as $rate) {
            $prepareCurrencies[] = [
                'from' => $currencies->where('curr_id', $rate['from'])->first(),
                'to' => $currencies->where('curr_id', $rate['to'])->first(),
                'rate' => $rate,
            ];
        }

        return view('listCurrency', ['currencies' => $prepareCurrencies]);
    }
}
