<?php

declare(strict_types=1);

namespace Auth\Domain\Exceptions;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class PasswordStrengthException extends DomainException
{
    #[HttpStatusCode(422)]
    public const MESSAGE = 'パスワードは８文字以上で、少なくとも大文字１文字、数字１文字、特殊文字１が１文字含まれている必要があります。';
}
