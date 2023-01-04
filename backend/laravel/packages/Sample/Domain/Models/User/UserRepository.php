<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User;

use Sample\Domain\Models\User\Exception\UserNotFoundException;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserId;

interface UserRepository
{
    /**
     * @param User $user
     *
     * @return void
     */
    public function create(User $user): void;

    /**
     * @param User $user
     *
     * @return void
     */
    public function update(User $user): void;

    /**
     * @param UserEmail $userEmail
     *
     * @return User|null
     */
    public function findByEmail(UserEmail $userEmail): ?User;

    /**
     * @param UserId $userId
     *
     * @return User
     * @throws UserNotFoundException
     */
    public function getByUserId(UserId $userId): User;
}
