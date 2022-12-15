<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Repositories\InMemory;

use SampleHR\Domain\Models\User\Exception\UserNotFoundException;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\UserRepository;
use SampleHR\Domain\Models\User\ValueObject\UserEmail;

/**
 *
 */
final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var array<User>
     */
    private array $users = [];

    /**
     * @param User $user
     *
     * @return User
     */
    public function create(User $user): User
    {
        $this->users[] = $user;
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function update(User $user): User
    {
        if (! array_key_exists($user->userId->value(), $this->users)) {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE);
        }
        return $user;
    }

    /**
     * @param UserEmail $userEmail
     *
     * @return User|null
     */
    public function findByEmail(UserEmail $userEmail): ?User
    {
        $users = array_filter($this->users, function (User $user) use ($userEmail) {
            return $user->userEmail->equals($userEmail);
        });

        if (count($users) === 0) {
            return null;
        }

        return array_shift($users);
    }
}
