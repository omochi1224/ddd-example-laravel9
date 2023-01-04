<?php

declare(strict_types=1);

namespace Sample\Infrastructure\Encryption;

use Base\DomainSupport\ValueObject\StringValueObject;
use Illuminate\Support\Facades\Hash;
use Sample\Domain\Models\User\HashService;
use Sample\Domain\Models\User\ValueObject\UserHashPassword;

final class PasswordHashEncryption implements HashService
{
    /**
     * @param StringValueObject $raw
     *
     * @return UserHashPassword
     */
    public function hashing(StringValueObject $raw): UserHashPassword
    {
        return UserHashPassword::of(Hash::make($raw->value()));
    }
}
