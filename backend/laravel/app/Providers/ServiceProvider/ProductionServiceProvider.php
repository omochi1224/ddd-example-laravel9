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

/**
 *
 */
final class ProductionServiceProvider implements Provider
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
