<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\User\ValueObject;

use Base\DomainSupport\ValueObject\StringValueObject;
use SampleHR\Domain\Models\User\Exception\PasswordStrengthException;

readonly final class UserRawPassword extends StringValueObject
{
    /**
     * @throws PasswordStrengthException
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

        parent::__construct($value);
    }
}
