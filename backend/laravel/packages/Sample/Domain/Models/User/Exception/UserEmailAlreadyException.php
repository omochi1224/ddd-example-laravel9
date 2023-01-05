<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class UserEmailAlreadyException extends DomainException
{
    #[HttpStatusCode(422)]
    public const MESSAGE = 'すでに登録済みのメールアドレスです。';
}
