<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User\Adapter;

use Base\AdapterSupport\AdapterOutput;
use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRegisterNotify;

readonly final class TemporaryRegisterUserOutput implements AdapterOutput
{
    public function __construct(
        public User $user,
        public UserRegisterNotify $notify
    ) {
    }
}
