<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

final readonly class UserHashPassword extends Password
{
    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function of(string $password): UserHashPassword
    {
        return new UserHashPassword($password);
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
