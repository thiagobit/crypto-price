<?php

namespace App\Providers;

use App\Services\CoinGeckoService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class CoinGeckoServiceProvider extends ServiceProvider
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
        $this->app->singleton(CoinGeckoService::class, function ($app) {
            return new CoinGeckoService(new Client());
        });
    }
}
