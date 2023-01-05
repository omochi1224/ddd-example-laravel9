<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Administrator;

use Base\RoleObjectSupport\RoleObject;
use Sample\Domain\Models\User\IUser;
use Sample\Domain\Models\User\User;

/**
 *
 */
interface IAdministrator extends RoleObject
{
    /**
     * @param User $user
     *
     * @return Administrator
     */
    public static function of(User $user): Administrator;

    /**
     * @param IUser $user
     *
     * @return void
     */
    public function accountBan(IUser $user): void;

    /**
     * @param IUser $user
     *
     * @return void
     */
    public function unsubscribe(IUser $user): void;
}
