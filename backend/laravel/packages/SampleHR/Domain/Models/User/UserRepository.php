<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\User;

use SampleHR\Domain\Models\User\ValueObject\UserEmail;

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
     * @return User
     */
    public function update(User $user): void;

    /**
     * @param UserEmail $userEmail
     *
     * @return User|null
     */
    public function findByEmail(UserEmail $userEmail): ?User;
}
