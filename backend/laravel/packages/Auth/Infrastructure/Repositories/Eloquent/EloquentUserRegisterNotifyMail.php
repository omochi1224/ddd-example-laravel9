<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Repositories\Eloquent;


use Auth\Domain\Models\User\UserRegisterNotifyMail;
use Auth\Domain\Models\User\UserRegisterNotifyMailRepository;

final class EloquentUserRegisterNotifyMail implements UserRegisterNotifyMailRepository
{
    /**
     * @param UserRegisterNotifyMail $userRegisterNotifyMail
     *
     * @return UserRegisterNotifyMail
     */
    public function insert(UserRegisterNotifyMail $userRegisterNotifyMail): UserRegisterNotifyMail
    {

    }
}
