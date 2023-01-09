<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

/**
 *
 */
final readonly class SocialLoginNoPassword extends Password
{
    public static function of(): SocialLoginNoPassword
    {
        return new SocialLoginNoPassword(null);
    }

    public function value(): string|null
    {
        return $this->value;
    }
}
