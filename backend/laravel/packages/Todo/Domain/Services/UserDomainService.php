<?php

declare(strict_types=1);

namespace Todo\Domain\Services;

use Todo\Domain\Models\User\UserRepository;
use Todo\Domain\Models\User\ValueObject\UserEmail;

final readonly class UserDomainService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function isUserEmailExists(UserEmail $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }
}
