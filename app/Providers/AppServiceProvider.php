<?php

namespace App\Providers;

use App\Contracts\Services\SslClientInterface;
use App\Contracts\Services\WhoisClientInterface;
use App\Service\Ssl\NativeSslClient;
use App\Service\Whois\IodevWhoisClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WhoisClientInterface::class, IodevWhoisClient::class);
        $this->app->bind(SslClientInterface::class, NativeSslClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
