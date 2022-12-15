<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Encryption;

use SampleHR\Domain\Models\User\HashService;
use SampleHR\Domain\Models\User\ValueObject\UserHashPassword;
use Base\DomainSupport\ValueObject\StringValueObject;
use Illuminate\Support\Facades\Hash;

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
