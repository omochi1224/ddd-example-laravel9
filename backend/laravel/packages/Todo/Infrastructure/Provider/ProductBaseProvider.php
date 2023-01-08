<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Provider;

use Base\LaravelProviderSupport\BaseProvider;
use Illuminate\Foundation\Application;
use Todo\Domain\Models\User\UserRepository;
use Todo\Infrastructure\Repositories\InMemory\InMemoryUserRepository;

final readonly class ProductBaseProvider implements BaseProvider
{
    /**
     * @param Application $app
     */
    public function __construct(private Application $app)
    {
    }

    public function register(): void
    {
        // TODO: Implement register() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    /**
     * フレームワークの機能と接続
     */
    public function registerLibrary(): void
    {
        // TODO: Implement registerLibrary() method.
    }

    public function registerFactory(): void
    {
        // TODO: Implement registerFactory() method.
    }

    public function registerRepositories(): void
    {
        $this->app->bind(UserRepository::class, InMemoryUserRepository::class);
    }

    public function registerQueryService(): void
    {
        // TODO: Implement registerQueryService() method.
    }

    public function registerIO(): void
    {
        // TODO: Implement registerIO() method.
    }
}
