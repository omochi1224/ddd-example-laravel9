<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

use Todo\Domain\Models\User\Exception\PasswordStrengthException;

readonly final class UserRawPassword extends Password
{
    /**
     * @throws PasswordStrengthException
     */
    private function __construct(string $value)
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

    /**
     * @throws PasswordStrengthException
     */
    public static function of(string $password): UserRawPassword
    {
        return new UserRawPassword($password);
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
