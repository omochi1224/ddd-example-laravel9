<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User;

use Base\DomainSupport\ValueObject\StringValueObject;
use Sample\Domain\Models\User\ValueObject\UserHashPassword;

/**
 * パスワードのハッシュ化
 */
interface HashService
{
    /**
     * @return UserHashPassword
     */
    public function hashing(StringValueObject $raw): StringValueObject;
}
