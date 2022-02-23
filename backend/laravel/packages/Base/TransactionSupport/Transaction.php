<?php

declare(strict_types=1);

namespace Base\TransactionSupport;

use Base\UseCaseSupport\UseCaseResult;

/**
 * Interface Transaction
 *
 * @package Basic\Transaction
 */
interface Transaction
{
    /**
     * @param callable $transactionScope
     *
     * @return object|null
     */
    public function scope(callable $transactionScope): ?UseCaseResult;

    /**
     * @return void
     */
    public function begin(): void;

    /**
     * @return void
     */
    public function commit(): void;

    /**
     * @return void
     */
    public function rollback(): void;
}
