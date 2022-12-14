<?php

declare(strict_types=1);

namespace Auth\Application\UseCases\Register\Adapter;

use Auth\Domain\Models\User\User;

/**
 *
 */
interface RegisterUserOutput
{

    /**
     * @param User $user
     *
     * @return User
     */
    public function setUser(User $user): User;

    /**
     * @return User
     */
    public function getUser(): User;
}
