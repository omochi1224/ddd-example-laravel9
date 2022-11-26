<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User;

interface UserRegisterNotifyMailRepository
{
    /**
     * @param UserRegisterNotifyMail $userRegisterNotifyMail
     *
     * @return UserRegisterNotifyMail
     */
    public function create(UserRegisterNotifyMail $userRegisterNotifyMail): UserRegisterNotifyMail;
}
