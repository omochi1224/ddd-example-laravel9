<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;

use Todo\Domain\Models\User\ValueObject\Password;
use Todo\Domain\Models\User\ValueObject\UserHashPassword;

/**
 * パスワードのハッシュ化
 */
interface HashService
{
    public function hashing(Password $raw): UserHashPassword;
}
