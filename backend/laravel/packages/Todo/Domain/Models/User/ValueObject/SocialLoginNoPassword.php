<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

/**
 *
 */
final readonly class SocialLoginNoPassword extends Password
{
    private function __construct()
    {
        parent::__construct(null);
    }

    public function value(): null
    {
        return null;
    }

    public static function of(): SocialLoginNoPassword
    {
        return new SocialLoginNoPassword();
    }
}
