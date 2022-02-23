<?php

declare(strict_types=1);

namespace App\lib;

use Base\LoggerSupport\Logger;
use Base\TransactionSupport\Transaction;
use Base\UseCaseSupport\UseCaseResult;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * LaravelTransaction
 */
final class LaravelDbTransaction implements Transaction
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
     * @throws Throwable
     */
    public function scope(callable $transactionScope): ?UseCaseResult
    {
        $this->logger->info('トランザクション');
        return DB::transaction($transactionScope);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function begin(): void
    {
        $this->logger->info('トランザクション開始');
        DB::beginTransaction();
    }

    /**
     * @return mixed|void
     */
    public function commit(): void
    {
        $this->logger->info('トランザクション終了');
        DB::commit();
    }

    /**
     * @return void
     */
    public function rollback(): void
    {
        $this->logger->info('ロールバック');
        DB::rollBack();
    }
}
