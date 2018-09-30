<?php

namespace App\Providers;

use App\Services\ApiService;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        return $this->app->bind('api', function ($app) {
            return new ApiService($app->make('session'), [
                'responseType'  => config('api.response_type'),
                'clientId'      => config('api.client_id'),
                'clientSecret'  => config('api.client_secret'),
                'scope'         => config('api.scope'),
                'baseOauthUrl'  => config('api.oauth_url'),
                'baseUrlApiV1'  => config('api.base_api_url_v1'),
            ]);
        });
    }
}
