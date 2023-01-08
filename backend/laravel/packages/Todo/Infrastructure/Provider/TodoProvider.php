<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Provider;

use Base\LaravelProviderSupport\BaseProvider;
use Base\LaravelProviderSupport\Provider;
use Illuminate\Foundation\Application;
use OutOfBoundsException;

final readonly class TodoProvider implements Provider
{
    public function __construct(private Application $app, private string $env = 'testing')
    {
    }

    public function provider(): BaseProvider
    {
        return match ($this->env) {
            'testing' => new TestBaseProvider($this->app),
            'local' => new LocalBaseProvider($this->app),
            'staging', 'production' => new ProductBaseProvider($this->app),
            default => throw new OutOfBoundsException(),
        };
    }
}
