<?php

declare(strict_types=1);

namespace Auth\Adapter\Http;

use Auth\Domain\Models\User\User;

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
     * @return array<string, mixed>
     */
    public function value(): array
    {
        return [
            'user_id' => $this->user->userId->value(),
            'email' => $this->user->userEmail->value(),
            'password' => $this->user->userPassword->value(),
        ];
    }
}
