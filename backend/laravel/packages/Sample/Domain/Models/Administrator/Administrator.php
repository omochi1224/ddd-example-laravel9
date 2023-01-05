<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Administrator;

use Sample\Domain\Models\User\IUser;
use Sample\Domain\Models\User\ValueObject\UserStatus;

/**
 *
 */
final readonly class Administrator implements IAdministrator
{
    /**
     * @param IUser $user
     */
    private function __construct(private IUser $user)
    {
    }

    /**
     * @param IUser $user
     *
     * @return Administrator
     */
    public static function of(IUser $user): Administrator
    {
        return new Administrator($user);
    }

    /**
     *  アカウント強制退会
     *
     * @param IUser $user
     *
     * @return void
     */
    public function accountBan(IUser $user): void
    {
        $user->changeStatus(UserStatus::Ban);
    }

    /**
     * @param IUser $user
     *
     * @return void
     */
    public function unsubscribe(IUser $user): void
    {
        $user->changeStatus(UserStatus::Unsubscribe);
    }
}
