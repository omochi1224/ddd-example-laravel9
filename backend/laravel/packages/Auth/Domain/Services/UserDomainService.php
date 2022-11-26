<?php

declare(strict_types=1);

namespace Auth\Domain\Services;

use Auth\Domain\Models\User\UserRepository;
use Auth\Domain\Models\User\ValueObject\UserEmail;

final class UserDomainService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @param UserEmail $userEmail
     *
     * @return bool
     */
    public function isEmailAlready(UserEmail $userEmail): bool
    {
        return ! ($this->userRepository->findByEmail($userEmail) === null);
    }
}
