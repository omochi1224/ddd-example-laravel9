<?php

declare(strict_types=1);

namespace Todo\Application\UseCases\TemporarySNSRegister;

use Todo\Domain\Models\User\IUser;

final readonly class TemporarySNSRegisterOutputAdapter
{
    public function __construct(private IUser $user)
    {
    }

    public function getUser(): IUser
    {
        return $this->user;
    }
}
