<?php

declare(strict_types=1);

namespace App\Providers\ServiceProvider;

/**
 * Interface Provider
 */
interface Provider
{
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
}
