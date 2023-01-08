<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class UserAlreadyDefinitiveException extends DomainException
{
    #[HttpStatusCode(422)]
    public const MESSAGE = 'アカウントはすでに本登録済みです。';
}
