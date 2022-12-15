<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Repositories\InMemory;

use Auth\Domain\Models\User\UserRegisterNotifyMail;
use Auth\Domain\Models\User\UserRegisterNotifyMailRepository;

/**
 *
 */
final class InMemoryUserRegisterNotifyMail implements UserRegisterNotifyMailRepository
{
    /**
     * @var array
     */
    public array $mails = [];

    /**
     * @param UserRegisterNotifyMail $userRegisterNotifyMail
     *
     * @return UserRegisterNotifyMail
     */
    public function create(UserRegisterNotifyMail $userRegisterNotifyMail): UserRegisterNotifyMail
    {
        $this->mails[] = $userRegisterNotifyMail;
        return $userRegisterNotifyMail;
    }
}
