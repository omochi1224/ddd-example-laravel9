<?php

namespace App\Providers;

use Base\LaravelProviderSupport\Provider;
use Illuminate\Support\ServiceProvider;
use Todo\Infrastructure\Provider\TodoProvider as TodoProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        array_map(fn(Provider $provider) => $provider->provider()->register(), $this->provider());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        array_map(fn(Provider $provider) => $provider->provider()->boot(), $this->provider());
    }

    /**
     * @return Provider[]
     */
    private function provider(): array
    {
        $env = config('app.env');
        return [
            new TodoProvider($this->app, $env),
        ];
    }
}
