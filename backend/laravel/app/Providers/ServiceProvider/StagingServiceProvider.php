<?php

declare(strict_types=1);

namespace App\Providers\ServiceProvider;

use App\lib\LaravelDbTransaction;
use App\lib\LaravelLogger;
use Base\LoggerSupport\Logger;
use Base\RequestSupport\Request;
use Base\TransactionSupport\Transaction;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserInput;
use Sample\Domain\Models\Notification\NotificationSender;
use Sample\Domain\Models\User\HashService;
use Sample\Domain\Models\User\UserRepository;
use Sample\Infrastructure\Encryption\PasswordHashEncryption;
use Sample\Infrastructure\Notification\DummyNotificationSender;
use Sample\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use Sample\Presentation\Request\RegisterUserLaravelInputRequest;

final class StagingServiceProvider implements Provider
{
    public function __construct(private readonly Application $app)
    {
    }

    public function register(): void
    {
        $this->registerLibrary();
        $this->registerFactory();
        $this->registerRepositories();
        $this->registerQueryService();
        $this->registerIO();

        $this->app->bind(HashService::class, PasswordHashEncryption::class);
        $this->app->bind(NotificationSender::class, DummyNotificationSender::class);
    }

    public function registerLibrary(): void
    {
        $this->app->bind(Transaction::class, LaravelDbTransaction::class);
        $this->app->bind(Logger::class, LaravelLogger::class);
        $this->app->bind(Request::class, FormRequest::class);
    }

    public function registerFactory(): void
    {
    }

    public function registerRepositories(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function registerQueryService(): void
    {
    }

    public function boot(): void
    {
    }

    public function registerIO(): void
    {
        $this->app->bind(TemporaryRegisterUserInput::class, RegisterUserLaravelInputRequest::class);
    }
}
