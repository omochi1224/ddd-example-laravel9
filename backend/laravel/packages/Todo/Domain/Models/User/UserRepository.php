<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User;


use Todo\Domain\Models\User\Exception\UserNotFoundException;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Models\User\ValueObject\UserId;

interface UserRepository
{
    public function create(IUser $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function update(IUser $user): void;

    public function findByEmail(UserEmail $userEmail): ?IUser;

    /**
     * @throws UserNotFoundException
     */
    public function getByIUserId(UserId $userId): IUser;
}
