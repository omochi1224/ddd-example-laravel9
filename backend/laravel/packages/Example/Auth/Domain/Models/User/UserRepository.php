<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User;

use Auth\Domain\Models\User\ValueObject\UserEmail;

interface UserRepository
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function create(User $user): User;

    /**
     * @param User $user
     *
     * @return User
     */
    public function update(User $user): User;

    /**
     * @param UserEmail $userEmail
     *
     * @return User|null
     */
    public function findByEmail(UserEmail $userEmail): ?User;
}
