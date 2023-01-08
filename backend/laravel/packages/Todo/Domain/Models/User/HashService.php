<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;

use Base\DomainSupport\ValueObject\StringValueObject;
use Todo\Domain\Models\User\ValueObject\UserHashPassword;

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
