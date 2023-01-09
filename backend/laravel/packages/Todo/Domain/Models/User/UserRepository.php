<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;

use Todo\Domain\Models\User\Exception\UserNotFoundException;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Models\User\ValueObject\UserId;

interface UserRepository
{
    public function create(User $user): void;

    public function update(User $user): void;

    public function findByEmail(UserEmail $userEmail): ?User;

    /**
     * @throws UserNotFoundException
     */
    public function getByUserId(UserId $userId): User;
}
