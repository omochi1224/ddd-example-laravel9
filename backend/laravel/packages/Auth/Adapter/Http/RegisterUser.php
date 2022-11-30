<?php

declare(strict_types=1);

namespace Auth\Adapter\Http;

final class RegisterUser
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(public readonly string $email, public readonly string $password)
    {
    }
}
