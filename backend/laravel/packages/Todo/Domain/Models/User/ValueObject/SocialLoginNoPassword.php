<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

use Base\DomainSupport\ValueObject\NullValueObject;

/**
 *
 */
final readonly class SocialLoginNoPassword extends NullValueObject implements Password
{
}
