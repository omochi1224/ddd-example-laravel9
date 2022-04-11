<?php

declare(strict_types=1);

namespace Base\DomainSupport\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class InvalidEmailAddressException extends DomainException
{
    #[HttpStatusCode(422)]
    public const MESSAGE = 'メールアドレスが正しくありません。';
}
