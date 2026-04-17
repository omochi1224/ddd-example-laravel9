<?php

declare(strict_types=1);

namespace Sample\Domain\Models\Administrator;

use Sample\Domain\Models\User\IUser;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\ValueObject\UserStatus;

final readonly class Administrator implements IAdministrator
{
    private function __construct(private IUser $user)
    {
    }

    public static function of(User $user): Administrator
    {
        return new Administrator($user);
    }

    public function accountBan(): void
    {
        $this->user->changeStatus(UserStatus::Ban);
    }

    public function unsubscribe(): void
    {
        $this->user->changeStatus(UserStatus::Unsubscribe);
    }
}
