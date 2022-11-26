<?php

declare(strict_types=1);

namespace App\Providers\ServiceProvider;

use App\lib\LaravelDbTransaction;
use App\lib\LaravelLogger;
use Auth\Domain\Models\User\HashService;
use Auth\Domain\Models\User\UserRegisterNotifyMailRepository;
use Auth\Domain\Models\User\UserRepository;
use Auth\Infrastructure\Encryption\PasswordHashEncryption;
use Auth\Infrastructure\Repositories\Eloquent\EloquentUserRepository;
use Auth\Infrastructure\Repositories\InMemory\InMemoryUserRegisterNotifyMail;
use Base\LoggerSupport\Logger;
use Base\RequestSupport\Request;
use Base\TransactionSupport\Transaction;
use Common\Domain\Models\Email\EmailSender;
use Common\Infrastructure\Sender\Mailable\MailableEmailSender;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\FormRequest;

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

        $this->app->bind(EmailSender::class, MailableEmailSender::class);
        $this->app->bind(HashService::class, PasswordHashEncryption::class);
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
        $this->app->bind(UserRegisterNotifyMailRepository::class, InMemoryUserRegisterNotifyMail::class);
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
}
