<?php

namespace App\Providers;

use App\Providers\ServiceProvider\LocalServiceProvider;
use App\Providers\ServiceProvider\ProductionServiceProvider;
use App\Providers\ServiceProvider\Provider;
use App\Providers\ServiceProvider\TestServiceProvider;
use Illuminate\Support\ServiceProvider;
use OutOfBoundsException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $provider = $this->provider();
        $provider->register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * @return \App\Providers\ServiceProvider\Provider
     */
    private function provider(): Provider
    {
        $env = config('app.env');
        return match ($env) {
            'testing' => new TestServiceProvider($this->app),
            'local' => new LocalServiceProvider($this->app),
            'staging', 'production' => new ProductionServiceProvider($this->app),
            default => throw new OutOfBoundsException(),
        };
    }
}
