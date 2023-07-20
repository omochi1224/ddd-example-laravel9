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
        return ! ($this->userRepository->findByEmail($user->userEmail) === null);
    }

    /**
     * @param UserId $userId
     *
     * @return bool
     */
    public function doesUserIdExist(UserId $userId): bool
    {
        try {
            $this->userRepository->getByUserId($userId);
            return true;
        } catch (UserNotFoundException $exception) {
            return false;
        }
    }
}
