<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repositories\Eloquent;

use Auth\Domain\Models\User\User;
use Auth\Domain\Models\User\UserRepository;
use Auth\Domain\Models\User\ValueObject\UserEmail;
use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Auth\Domain\Models\User\ValueObject\UserId;
use Auth\Infrastructure\EloquentModels\EloquentUser;
use Base\DomainSupport\Exception\InvalidUuidException;

/**
 *
 */
final class EloquentUserRepository implements UserRepository
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function create(User $user): User
    {
        $model = new EloquentUser();
        $model
            ->fill($this->toArray($user))
            ->save();
        return $user;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function update(User $user): User
    {
        EloquentUser::where('user_id', $user->userId->value())
            ->update($this->toArray($user));
        return $user;
    }

    /**
     * @param UserEmail $userEmail
     *
     * @return User|null
     * @throws InvalidUuidException
     */
    public function findByEmail(UserEmail $userEmail): ?User
    {
        $user = EloquentUser::where('email', $userEmail->value())
            ->first();

        if ($user === null) {
            return null;
        }

        return User::restoreFromDB(
            UserId::of($user->user_id),
            UserEmail::of($user->email),
            UserHashPassword::of($user->password),
            null,
        );
    }

    /**
     * @param User $user
     *
     * @return array<string, mixed>
     */
    private function toArray(User $user): array
    {
        return [
            'user_id' =>  $user->userId->value(),
            'email' => $user->userEmail->value(),
            'password' => $user->userPassword->value(),
        ];
    }
}
