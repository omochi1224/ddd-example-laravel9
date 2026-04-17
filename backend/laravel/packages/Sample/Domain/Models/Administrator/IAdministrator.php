<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Administrator;

use Base\RoleObjectSupport\RoleObject;
use Sample\Domain\Models\User\IUser;
use Sample\Domain\Models\User\User;

interface IAdministrator extends RoleObject
{
    public static function of(User $user): Administrator;

    public function accountBan(): void;

    public function unsubscribe(): void;
}
