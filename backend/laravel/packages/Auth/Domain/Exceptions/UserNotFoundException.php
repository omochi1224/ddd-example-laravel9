<?php

declare(strict_types=1);

namespace Auth\Domain\Exceptions;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class UserNotFoundException extends DomainException
{
    #[HttpStatusCode(404)]
    public const MESSAGE = 'ユーザが見つかりませんでした。';
}
