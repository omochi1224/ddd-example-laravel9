<?php

declare(strict_types=1);

namespace SampleHR\Domain\Services;

use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;

final readonly class UserDomainService
{
    public function __construct(private UserRepository $userRepository)
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
