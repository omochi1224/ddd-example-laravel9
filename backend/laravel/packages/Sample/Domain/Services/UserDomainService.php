<?php

declare(strict_types=1);

namespace Sample\Domain\Services;

use Sample\Domain\Models\User\Exception\UserNotFoundException;
use Sample\Domain\Models\User\IUser;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserId;

/**
 *
 */
final readonly class UserDomainService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function isEmailAlready(IUser $user): bool
    {
        return ! ($this->userRepository->findByEmail($user->userEmail) === null);
    }

    public function doesUserIdExist(UserId $userId): bool
    {
        try {
            $this->userRepository->getByUserId($userId);
            return true;
        } catch (UserNotFoundException) {
            return false;
        }
    }
}
