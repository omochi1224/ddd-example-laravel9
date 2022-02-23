<?php

declare(strict_types=1);

namespace Base\DebugSupport\Transaction;

use Base\LoggerSupport\Logger;

final class NopTransaction
{
    /**
     * @param Logger $logger
     */
    public function __construct(private Logger $logger)
    {
    }

    /**
     * @param callable $transactionScope
     *
     * @return object|null
     */
    public function scope(callable $transactionScope): ?object
    {
        $this->logger->info('トランザクション');
        return $transactionScope();
    }

    /**
     * @return void
     */
    public function begin(): void
    {
        $this->logger->info('トランザクション開始');
    }

    /**
     * @return void
     */
    public function commit(): void
    {
        $this->logger->info('トランザクション終了');
    }

    /**
     * @return void
     */
    public function rollback(): void
    {
        $this->logger->info('ロールバック');
    }
}
