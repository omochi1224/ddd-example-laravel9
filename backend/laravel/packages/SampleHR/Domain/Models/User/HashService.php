<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\User;

use Base\DomainSupport\ValueObject\StringValueObject;
use SampleHR\Domain\Models\User\ValueObject\UserHashPassword;

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
