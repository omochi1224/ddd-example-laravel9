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
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @param IUser $user
     *
     * @return bool
     */
    public function isEmailAlready(IUser $user): bool
    {
        return !($this->userRepository->findByEmail($user->userEmail) === null);
    }

    /**
     * @throws UserNotFoundException
     */
    public function isIdAlready(UserId $userId): IUser
    {
        return $this->userRepository->getByUserId($userId);
    }
}
