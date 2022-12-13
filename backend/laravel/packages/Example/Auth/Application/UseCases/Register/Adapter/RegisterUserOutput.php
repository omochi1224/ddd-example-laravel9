<?php

declare(strict_types=1);

namespace Auth\Application\UseCases\Register\Adapter;

use Auth\Domain\Models\User\User;
use Base\AdapterSupport\HttpOutput;

/**
 *
 */
final class RegisterUserOutput extends HttpOutput
{
    /**
     * @param User $user
     */
    public function __construct(private readonly User $user)
    {
    }

    /**
     * @return User
     */
    public function value(): User
    {
        return $this->user;
    }
}
