<?php

declare(strict_types=1);

namespace Base\LaravelProviderSupport;
use Illuminate\Foundation\Application;


/**
 * Interface Provider
 */
interface BaseProvider
{
    /**
     * @param Application $app
     */
    public function __construct(Application $app);

    /**
     * @return void
     */
    public function register(): void;

    /**
     * @return void
     */
    public function boot(): void;

    /**
     * フレームワークの機能と接続
     *
     * @return void
     */
    public function registerLibrary(): void;

    /**
     * @return void
     */
    public function registerFactory(): void;

    /**
     * @return void
     */
    public function registerRepositories(): void;

    /**
     * @return void
     */
    public function registerQueryService(): void;

    /**
     * @return void
     */
    public function registerIO(): void;
}
