<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User\ValueObject;

use Auth\Domain\Models\User\Exception\PasswordStrengthException;
use Base\DomainSupport\ValueObject\StringValueObject;

final class UserPassword extends StringValueObject
{
    /**
     * @throws \Auth\Domain\Models\User\Exception\PasswordStrengthException
     */
    public function __construct(string $value)
    {
        $uppercase = preg_match('@[A-Z]@', $value);
        $lowercase = preg_match('@[a-z]@', $value);
        $number = preg_match('@[0-9]@', $value);
        $specialChars = preg_match('@[^\w]@', $value);

        if (! $uppercase || ! $lowercase || ! $number || ! $specialChars || strlen($value) < 8) {
            throw new PasswordStrengthException(PasswordStrengthException::MESSAGE);
        }

        $this->value = $value;
    }
}
