<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User\ValueObject;

use Base\DomainSupport\ValueObject\UuidIdentifier;

readonly final class UserId extends UuidIdentifier
{
}
