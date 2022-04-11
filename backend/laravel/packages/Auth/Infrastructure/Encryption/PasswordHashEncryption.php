<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Encryption;

use Auth\Domain\Models\User\HashService;
use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Base\DomainSupport\ValueObject\StringValueObject;
use Illuminate\Support\Facades\Hash;

final class PasswordHashEncryption implements HashService
{
    /**
     * @param StringValueObject $userPassword
     *
     * @return UserHashPassword
     */
    public function hashing(StringValueObject $userPassword): UserHashPassword
    {
        return UserHashPassword::of(Hash::make($userPassword->value()));
    }
}
