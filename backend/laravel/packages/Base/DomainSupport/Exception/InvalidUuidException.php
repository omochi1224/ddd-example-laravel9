<?php

declare(strict_types=1);

namespace Base\DomainSupport\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

/**
 * 識別子エラー
 */
final class InvalidUuidException extends DomainException
{
    #[HttpStatusCode(422)]
    private const MESSAGE = 'UUIDの形式が間違っています。';
}
