<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

readonly final class UserHashPassword extends Password
{
    public static function of(string $password): UserHashPassword
    {
        return new UserHashPassword($password);
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
