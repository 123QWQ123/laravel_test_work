<?php

namespace App\Providers;

use App\Services\ApiService;
use App\Services\ClientAuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton(ClientAuthService::class, function () {
            return new ClientAuthService([
                'responseType'  => config('api.response_type'),
                'clientId'      => config('api.client_id'),
                'clientSecret'  => config('api.client_secret'),
                'scope'         => config('api.scope'),
                'baseOauthUrl'  => config('api.oauth_url'),
                'code'  => session('code'),
            ]);
        });

        return $this->app->bind(ApiService::class, function () {
            return new ApiService([
                'baseUrlApiV1'  => config('api.base_api_url_v1'),
                'sid'  => session('sid'),
            ]);
        });
    }
}
