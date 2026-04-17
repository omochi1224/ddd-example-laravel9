<?php

declare(strict_types=1);

namespace Sample\Infrastructure\Repositories\Eloquent;

use Sample\Domain\Models\User\Exception\UserNotFoundException;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Domain\Models\User\ValueObject\UserHashPassword;
use Sample\Domain\Models\User\ValueObject\UserId;
use Sample\Domain\Models\User\ValueObject\UserStatus;
use Sample\Infrastructure\EloquentModels\EloquentUser;

/**
 *
 */
final class EloquentUserRepository implements UserRepository
{
    public function create(User $user): void
    {
        $model = new EloquentUser();
        $model->user_id = $user->userId->value();
        $model->email = $user->userEmail->value();
        $model->password = $user->userPassword->value();
        $model->status = $user->userStatus->value();
        $model->save();
    }

    public function update(User $user): void
    {
        $affected = EloquentUser::where('user_id', $user->userId->value())
            ->update([
                'email' => $user->userEmail->value(),
                'password' => $user->userPassword->value(),
                'status' => $user->userStatus->value(),
            ]);

        if ($affected === 0) {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE);
        }
    }

    public function findByEmail(UserEmail $userEmail): ?User
    {
        $user = EloquentUser::where('email', $userEmail->value())
            ->first();

        if ($user === null) {
            return null;
        }

        return User::restoreFromDb(
            UserId::of($user->user_id),
            UserEmail::of($user->email),
            UserHashPassword::of($user->password),
            UserStatus::from($user->status),
            null,
        );
    }

    public function getByUserId(UserId $userId): User
    {
        $user = EloquentUser::where('user_id', $userId->value())
            ->first();

        if ($user === null) {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE);
        }

        return User::restoreFromDb(
            UserId::of($user->user_id),
            UserEmail::of($user->email),
            UserHashPassword::of($user->password),
            UserStatus::from($user->status),
            null,
        );
    }
}
