<?php

declare(strict_types=1);

namespace Base\DebugSupport\Transaction;

use Base\LoggerSupport\Logger;

final readonly class NopTransaction
{
    public function __construct(private Logger $logger)
    {
    }

    public function scope(callable $transactionScope): ?object
    {
        $this->logger->info('トランザクション');
        return $transactionScope();
    }

    public function begin(): void
    {
        $this->logger->info('トランザクション開始');
    }

    public function commit(): void
    {
        $this->logger->info('トランザクション終了');
    }

    public function rollback(): void
    {
        $this->logger->info('ロールバック');
    }
}
