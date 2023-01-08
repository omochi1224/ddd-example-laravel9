<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Repositories\InMemory;

use Todo\Domain\Models\User\Exception\UserNotFoundException;
use Todo\Domain\Models\User\User;
use Todo\Domain\Models\User\UserRepository;
use Todo\Domain\Models\User\ValueObject\UserEmail;
use Todo\Domain\Models\User\ValueObject\UserId;

final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var array<User>
     */
    private array $users = [];

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

    public function findByEmail(UserEmail $userEmail): ?User
    {
        $users = array_filter($this->users, fn(User $user) => $user->userEmail->equals($userEmail));

        if (count($users) === 0) {
            return null;
        }

        return array_shift($users);
    }

    /**
     *
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
