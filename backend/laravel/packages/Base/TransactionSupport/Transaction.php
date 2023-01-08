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
     * @return object|null
     */
    public function scope(callable $transactionScope): ?UseCaseResult;

    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
