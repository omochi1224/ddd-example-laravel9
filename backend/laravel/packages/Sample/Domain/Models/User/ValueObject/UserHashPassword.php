<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User\ValueObject;

use Base\DomainSupport\ValueObject\StringValueObject;

readonly final class UserHashPassword extends StringValueObject implements Password
{
}
