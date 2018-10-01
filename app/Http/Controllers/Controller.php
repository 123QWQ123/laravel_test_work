<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\ClientAuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function oauthRedirectHandler(Request $request, ApiService $apiService, ClientAuthService $authService)
    {
        if ($request->has('code')) {
            $authService->setCode($request->get('code'));
            $userData = $authService->getUserData();

            if ($sid = $apiService->getSid($userData)) {
                session(['sid' => $sid]);
                return redirect()->route('currencies');
            }
        }

        return redirect()->route('login');
    }

    public function login(ClientAuthService $authService, ApiService $apiService) {
        return view('login', [
            'getUrlOauthUser' => $authService->getUrlOauthUser(),
            'isAuth' => $apiService->isAuth()
        ]);
    }
}
