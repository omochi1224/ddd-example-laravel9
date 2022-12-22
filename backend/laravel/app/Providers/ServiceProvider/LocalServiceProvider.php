<?php

declare(strict_types=1);

namespace App\Providers\ServiceProvider;

use App\lib\LaravelDbTransaction;
use App\lib\LaravelInput;
use App\lib\LaravelLogger;
use Base\LoggerSupport\Logger;
use Base\RequestSupport\Request;
use Base\TransactionSupport\Transaction;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;
use SampleHR\Domain\Models\Notification\NotificationSender;
use SampleHR\Domain\Models\User\HashService;
use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Infrastructure\Encryption\PasswordHashEncryption;
use SampleHR\Infrastructure\Notification\DummyNotificationSender;
use SampleHR\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use SampleHR\Infrastructure\Transfer\EmailTransfer;
use SampleHR\Presentation\Request\RegisterUserLaravelInputRequest;

/**
 *
 */
final class LocalServiceProvider implements Provider
{
    /**
     * @param Application $app
     */
    public function __construct(private readonly Application $app)
    {
    }

    /**
     *
     */
    public function register(): void
    {
        $this->registerLibrary();
        $this->registerFactory();
        $this->registerRepositories();
        $this->registerQueryService();
        $this->registerIO();

        $this->app->bind(HashService::class, PasswordHashEncryption::class);
//        $this->app->bind(Sender::class, EmailTransfer::class);
        $this->app->bind(NotificationSender::class, DummyNotificationSender::class);
    }

    /**
     * LibraryのIoc
     *
     * @return void
     */
    public function registerLibrary(): void
    {
        $this->app->bind(Transaction::class, LaravelDbTransaction::class);
        $this->app->bind(Logger::class, LaravelLogger::class);
        $this->app->bind(Request::class, FormRequest::class);
    }

    /**
     * @return void
     */
    public function registerFactory(): void
    {
    }

    /**
     * @return void
     */
    public function registerRepositories(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    /**
     * @return void
     */
    public function registerQueryService(): void
    {
    }

    /**
     *
     */
    public function boot(): void
    {
    }

    /**
     * @return void
     */
    public function registerIO(): void
    {
        $this->app->bind(RegisterUserInput::class, RegisterUserLaravelInputRequest::class);
    }
}
