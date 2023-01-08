<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Encryption;

use Base\DomainSupport\ValueObject\StringValueObject;
use Illuminate\Support\Facades\Hash;
use Todo\Domain\Models\User\ValueObject\UserHashPassword;

final readonly class PasswordHashEncryption
{
    public function hashing(StringValueObject $raw): UserHashPassword
    {
        return UserHashPassword::of(Hash::make($raw->value()));
    }
}
