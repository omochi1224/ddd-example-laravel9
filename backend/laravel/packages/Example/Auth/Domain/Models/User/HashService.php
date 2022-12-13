<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User;

use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Base\DomainSupport\ValueObject\StringValueObject;

/**
 * パスワードのハッシュ化
 */
interface HashService
{
    /**
     * @param StringValueObject $raw
     *
     * @return UserHashPassword
     */
    public function hashing(StringValueObject $raw): StringValueObject;
}
