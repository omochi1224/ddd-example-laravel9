<?php

declare(strict_types=1);

namespace Auth\Application\Dtos;

final class RegisterUserDto
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(public readonly string $email, public readonly string $password)
    {
    }
}
