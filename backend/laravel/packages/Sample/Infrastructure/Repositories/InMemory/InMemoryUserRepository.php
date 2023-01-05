<?php

declare(strict_types=1);

namespace Sample\Infrastructure\Repositories\InMemory;

use Sample\Domain\Models\User\Exception\UserNotFoundException;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserId;

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
     * @return void
     */
    public function create(User $user): void
    {
        $this->users[$user->userId->value()] = $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function update(User $user): void
    {
        if (! array_key_exists($user->userId->value(), $this->users)) {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE);
        }

        $this->users[$user->userId->value()] = $user;
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

    /**
     * @param UserId $userId
     *
     * @return User
     *
     * @throws UserNotFoundException
     */
    public function getByUserId(UserId $userId): User
    {
        if (! array_key_exists($userId->value(), $this->users)) {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE);
        }

        return $this->users[$userId->value()];
    }
}
