<?php

declare(strict_types=1);

namespace SampleHR\Application\UseCases\Register\Adapter;

use Base\AdapterSupport\AdapterOutput;
use SampleHR\Domain\Models\User\User;
use SampleHR\Domain\Models\User\UserRegisterNotify;

/**
 *
 */
readonly final class RegisterUserOutput implements AdapterOutput
{
    /**
     * @param User               $user
     * @param UserRegisterNotify $notify
     */
    public function __construct(
        public User $user,
        public UserRegisterNotify $notify
    ) {
    }
}
